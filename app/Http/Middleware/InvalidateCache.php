<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class InvalidateCache
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $tags Cache tags to invalidate (comma-separated)
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $tags = ''): Response
    {
        $response = $next($request);

        // Only invalidate cache for successful write operations
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) && 
            $response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            
            $this->invalidateUserCache($request, $tags);
        }

        return $response;
    }

    /**
     * Invalidate cache for the authenticated user.
     *
     * @param Request $request
     * @param string $tags
     * @return void
     */
    private function invalidateUserCache(Request $request, string $tags): void
    {
        if (!$request->user()) {
            return;
        }

        $userId = $request->user()->id;
        $tagsArray = $tags ? explode(',', $tags) : [];

        // If specific tags are provided, invalidate those patterns
        if (!empty($tagsArray)) {
            foreach ($tagsArray as $tag) {
                $pattern = "api_cache:{$userId}:*{$tag}*";
                $this->invalidateCachePattern($pattern);
            }
        } else {
            // Invalidate all user cache if no specific tags
            $pattern = "api_cache:{$userId}:*";
            $this->invalidateCachePattern($pattern);
        }
    }

    /**
     * Invalidate cache entries matching a pattern.
     *
     * @param string $pattern
     * @return void
     */
    private function invalidateCachePattern(string $pattern): void
    {
        // Note: This is a simple implementation. 
        // For production, consider using Redis with pattern deletion
        // or implement a tag-based caching system
        
        $cacheStore = Cache::getStore();
        
        if (method_exists($cacheStore, 'flush')) {
            // For simple implementations, you might need to flush all cache
            // or implement a more sophisticated pattern matching system
            
            // This is a placeholder - implement based on your cache driver
            // Cache::flush(); // Too aggressive
            
            // Better approach would be to use cache tags if your driver supports it
            // or maintain a registry of cache keys
        }
    }
}