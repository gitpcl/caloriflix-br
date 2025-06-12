<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class CacheService
{
    /**
     * Cache duration in minutes for different resource types.
     */
    private const CACHE_DURATIONS = [
        'user' => 120,        // 2 hours
        'profile' => 120,     // 2 hours
        'preferences' => 120, // 2 hours
        'foods' => 60,        // 1 hour
        'meals' => 30,        // 30 minutes
        'recipes' => 60,      // 1 hour
        'reports' => 15,      // 15 minutes
        'measurements' => 30, // 30 minutes
        'goals' => 60,        // 1 hour
        'reminders' => 60,    // 1 hour
    ];

    /**
     * Get cached data or execute callback and cache result.
     *
     * @param string $key
     * @param callable $callback
     * @param string $type
     * @param int|null $userId
     * @return mixed
     */
    public function remember(string $key, callable $callback, string $type = 'default', ?int $userId = null): mixed
    {
        $cacheKey = $this->buildKey($key, $userId);
        $duration = $this->getCacheDuration($type);

        return Cache::remember($cacheKey, now()->addMinutes($duration), $callback);
    }

    /**
     * Invalidate cache for a specific user and resource type.
     *
     * @param int $userId
     * @param string|array $types
     * @return void
     */
    public function invalidateForUser(int $userId, string|array $types): void
    {
        $types = is_array($types) ? $types : [$types];

        foreach ($types as $type) {
            $pattern = $this->buildKey("*{$type}*", $userId);
            $this->forgetPattern($pattern);
        }
    }

    /**
     * Cache a model with automatic invalidation setup.
     *
     * @param Model $model
     * @param string $key
     * @param callable $callback
     * @return mixed
     */
    public function rememberModel(Model $model, string $key, callable $callback): mixed
    {
        $modelKey = $this->buildModelKey($model, $key);
        $duration = $this->getCacheDuration($this->getModelType($model));

        return Cache::remember($modelKey, now()->addMinutes($duration), $callback);
    }

    /**
     * Invalidate cache for a specific model.
     *
     * @param Model $model
     * @return void
     */
    public function invalidateModel(Model $model): void
    {
        $pattern = $this->buildModelKey($model, '*');
        $this->forgetPattern($pattern);
    }

    /**
     * Clear all API cache for a user.
     *
     * @param int $userId
     * @return void
     */
    public function clearUserCache(int $userId): void
    {
        $pattern = $this->buildKey('*', $userId);
        $this->forgetPattern($pattern);
    }

    /**
     * Build cache key.
     *
     * @param string $key
     * @param int|null $userId
     * @return string
     */
    private function buildKey(string $key, ?int $userId = null): string
    {
        if ($userId) {
            return "api_cache:{$userId}:{$key}";
        }

        return "api_cache:{$key}";
    }

    /**
     * Build model-specific cache key.
     *
     * @param Model $model
     * @param string $key
     * @return string
     */
    private function buildModelKey(Model $model, string $key): string
    {
        $modelType = $this->getModelType($model);
        $userId = $model->user_id ?? null;

        return $this->buildKey("{$modelType}:{$model->id}:{$key}", $userId);
    }

    /**
     * Get model type from class name.
     *
     * @param Model $model
     * @return string
     */
    private function getModelType(Model $model): string
    {
        return strtolower(class_basename($model));
    }

    /**
     * Get cache duration for a specific type.
     *
     * @param string $type
     * @return int
     */
    private function getCacheDuration(string $type): int
    {
        return self::CACHE_DURATIONS[$type] ?? 30;
    }

    /**
     * Forget cache entries matching a pattern.
     *
     * @param string $pattern
     * @return void
     */
    private function forgetPattern(string $pattern): void
    {
        // This is a simplified implementation
        // In production, you might want to use Redis with SCAN command
        // or implement a more sophisticated pattern matching
        
        try {
            Cache::forget($pattern);
        } catch (\Exception $e) {
            // Log the error but don't fail the request
            logger()->warning('Cache invalidation failed', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get cache statistics.
     *
     * @return array
     */
    public function getStats(): array
    {
        // This would need to be implemented based on your cache driver
        return [
            'driver' => config('cache.default'),
            'durations' => self::CACHE_DURATIONS,
        ];
    }
}