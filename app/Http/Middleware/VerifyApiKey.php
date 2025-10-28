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
        $expectedApiKey = config('services.api.key');
        
        // Log the request for debugging
        // \Log::info('API Request', [
        //     'path' => $request->path(),
        //     'full_url' => $request->fullUrl(),
        //     'api_key' => $apiKey,
        //     'expected_key' => $expectedApiKey,
        //     'segments' => $request->segments()
        // ]);
        
        if (empty($apiKey) || $apiKey !== $expectedApiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or missing API Key in URL',
                'expected_format' => '/api/your-api-key/your-endpoint',
                'received_url' => $request->fullUrl()
            ], 401);
        }
        
        // Remove the API key from the segments to prevent it from being treated as a route parameter
        $path = $request->path();
        $path = preg_replace('#^api/[^/]+#', 'api', $path);
        $request->server->set('REQUEST_URI', '/'.$path);
        
        return $next($request);
    }
}
