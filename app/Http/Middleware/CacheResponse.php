<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param int $ttl TTL in minutes (default: 60)
     * @return Response
     */
    public function handle(Request $request, Closure $next, int $ttl = 60): Response
    {
        // Only cache GET requests
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        // Don't cache if user is not authenticated
        if (!$request->user()) {
            return $next($request);
        }

        // Generate cache key
        $cacheKey = $this->generateCacheKey($request);

        // Try to get from cache
        $cachedResponse = Cache::get($cacheKey);
        
        if ($cachedResponse) {
            return new JsonResponse(
                $cachedResponse['data'],
                $cachedResponse['status'],
                $cachedResponse['headers']
            );
        }

        // Process request
        $response = $next($request);

        // Only cache successful JSON responses
        if ($response instanceof JsonResponse && $response->getStatusCode() === 200) {
            $cacheData = [
                'data' => json_decode($response->getContent(), true),
                'status' => $response->getStatusCode(),
                'headers' => $response->headers->all(),
            ];

            // Add cache headers
            $response->headers->set('X-Cache-Status', 'MISS');
            $response->headers->set('X-Cache-TTL', $ttl * 60);

            // Store in cache
            Cache::put($cacheKey, $cacheData, now()->addMinutes($ttl));
        }

        return $response;
    }

    /**
     * Generate a unique cache key for the request.
     *
     * @param Request $request
     * @return string
     */
    private function generateCacheKey(Request $request): string
    {
        $url = $request->url();
        $queryParams = $request->query();
        $userId = $request->user()->id;

        // Sort query parameters for consistent cache keys
        ksort($queryParams);

        $key = sprintf(
            'api_cache:%d:%s:%s',
            $userId,
            $url,
            md5(serialize($queryParams))
        );

        return $key;
    }
}