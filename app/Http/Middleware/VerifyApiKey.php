<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip API key check for health check endpoint
        if ($request->is('api/health-check')) {
            return $next($request);
        }

        // Get the API key from the URL segment
        $apiKey = $request->segment(2); // Gets the second segment after /api/
        
        if ($apiKey !== config('services.api.key')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or missing API Key in URL'
            ], 401);
        }

        // Remove the API key from the request to avoid interfering with route parameters
        $request->request->remove('api_key');
        
        return $next($request);
    }
}
