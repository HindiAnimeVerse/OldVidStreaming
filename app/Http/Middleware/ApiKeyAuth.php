<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     * Validates API key from header or query parameter.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get API key from header or query param
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key required. Pass via X-API-Key header or api_key query parameter.'
            ], 401);
        }

        // Find user by API key
        $user = User::where('api_key', $apiKey)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key.'
            ], 401);
        }

        // Attach user to request for use in controllers
        $request->merge(['api_user' => $user]);
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
