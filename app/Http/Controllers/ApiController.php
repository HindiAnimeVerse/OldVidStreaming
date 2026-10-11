<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\GooglePhotosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * Search files using flexible matching.
     * Only returns files owned by the authenticated API user.
     * Returns ALL matching results (no limit).
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $user = $request->user();
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query must be at least 2 characters',
                'results' => []
            ], 400);
        }

        // Extract searchable tokens from query
        $tokens = $this->extractTokens($query);

        if (empty($tokens)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid search terms found',
                'results' => []
            ], 400);
        }

        // Search: require ALL tokens to match as words
        $results = $this->tokenSearch($user->id, $tokens);

        return response()->json([
            'success' => true,
            'query' => $query,
            'count' => $results->count(),
            'results' => $results->map(function ($file) {
                return [
                    'id' => $file->id,
                    'slug' => $file->slug,
                    'filename' => $file->filename,
                    'file_size' => $file->file_size,
                    'resolution' => $file->resolution,
                    'rip_type' => $file->rip_type,
                    'codec' => $file->codec,
                    'stream_url' => url('/v/' . $file->slug),
                    'download_url' => url('/d/' . $file->slug),
                ];
            })->values()
        ]);
    }

    /**
     * Get file details and streaming URLs.
     * Only returns file if owned by the authenticated API user.
     * 
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, string $slug)
    {
        $user = $request->user();
        $file = File::where('slug', $slug)->where('user_id', $user->id)->first();

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        // Get streaming sources
        $sources = [];
        try {
            $service = new GooglePhotosService();
            $sources = $service->getAllQualityUrlsWithStatus($file->media_url);
        } catch (\Exception $e) {
            // Sources unavailable
        }

        return response()->json([
            'success' => true,
            'file' => [
                'slug' => $file->slug,
                'filename' => $file->filename,
                'file_size' => $file->file_size,
                'resolution' => $file->resolution,
                'rip_type' => $file->rip_type,
                'codec' => $file->codec,
            ],
            'sources' => $sources
        ]);
    }

    /**
     * Extract clean tokens from search query.
     * Keeps ALL words (minimum 2 chars) - the token search requires ALL to match.
     */
    private function extractTokens(string $query): array
    {
        // Remove all punctuation except apostrophes within words
        $clean = preg_replace('/[^\w\s\'-]/u', ' ', $query);

        // Handle apostrophes (Li'l -> Lil, Sister's -> Sisters)
        $clean = preg_replace("/(?<=\w)'(?=\w)/", '', $clean);
        $clean = str_replace("'", ' ', $clean);

        // Split into words and filter (minimum 2 chars)
        $words = preg_split('/[\s\-_]+/', strtolower($clean));
        $words = array_filter($words, fn($w) => strlen($w) >= 2);

        return array_values(array_unique($words));
    }

    /**
     * Token-based search - requires ALL tokens present as whole words.
     * Uses REGEXP word boundaries to prevent "dan" matching "wandance".
     */
    private function tokenSearch(int $userId, array $tokens)
    {
        if (empty($tokens)) {
            return collect();
        }

        // Filter to tokens with 2+ chars
        $tokens = array_filter($tokens, fn($t) => strlen($t) >= 2);

        if (empty($tokens)) {
            return collect();
        }

        $queryBuilder = File::where('user_id', $userId);

        // Match each token as a whole word using REGEXP
        // Pattern: (^|[^a-z0-9])token([^a-z0-9]|$)
        // This ensures "dan" matches " dan " but not "wandance"
        foreach ($tokens as $token) {
            // Escape special regex characters for MySQL
            $escapedToken = addcslashes($token, '.[]()*+?{}^$|\\');
            // Word boundary pattern: non-alphanumeric before and after, or start/end
            $pattern = '(^|[^a-z0-9])' . $escapedToken . '([^a-z0-9]|$)';
            $queryBuilder->whereRaw("LOWER(REPLACE(filename, \"'\", '')) REGEXP ?", [$pattern]);
        }

        return $queryBuilder->get();
    }
}
