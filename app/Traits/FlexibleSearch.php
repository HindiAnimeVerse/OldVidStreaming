<?php

namespace App\Traits;

trait FlexibleSearch
{
    /**
     * Extract clean tokens from search query.
     * Removes all punctuation and normalizes for flexible matching.
     */
    protected function extractSearchTokens(string $query): array
    {
        // Remove all punctuation except apostrophes within words
        $clean = preg_replace('/[^\w\s\'-]/u', ' ', $query);

        // Handle apostrophes (Li'l -> Lil, don't -> dont)
        $clean = preg_replace("/(?<=\w)'(?=\w)/", '', $clean);
        $clean = str_replace("'", ' ', $clean);

        // Split into words and filter
        $words = preg_split('/[\s\-_]+/', strtolower($clean));
        $words = array_filter($words, fn($w) => strlen($w) >= 2);

        // Remove common words that don't help search
        $stopwords = ['the', 'and', 'for', 'with'];
        $words = array_filter($words, fn($w) => !in_array($w, $stopwords));

        return array_values(array_unique($words));
    }

    /**
     * Apply flexible token-based search to a query builder.
     * All tokens must be present in the specified column.
     * 
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $searchTerm
     * @param string $column Column to search (e.g., 'filename' or 'files.filename')
     * @return \Illuminate\Database\Query\Builder
     */
    protected function applyFlexibleSearch($query, string $searchTerm, string $column = 'filename')
    {
        $tokens = $this->extractSearchTokens($searchTerm);

        if (empty($tokens)) {
            return $query;
        }

        // Require ALL tokens to be present (case-insensitive)
        foreach ($tokens as $token) {
            $query->whereRaw("LOWER({$column}) LIKE ?", ['%' . $token . '%']);
        }

        return $query;
    }
}
