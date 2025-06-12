<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';
    
    protected $fillable = [
        'user_id',
        'name',
        'quantity',
        'unit',
        'protein',
        'fat',
        'carbohydrate',
        'fiber',
        'calories',
        'barcode',
        'is_favorite',
        'source',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'protein' => 'decimal:2',
        'fat' => 'decimal:2',
        'carbohydrate' => 'decimal:2',
        'fiber' => 'decimal:2',
        'calories' => 'decimal:2',
        'is_favorite' => 'boolean',
        'source' => 'string',
    ];

    /**
     * Get the user that owns the food item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the meal items that use this food.
     */
    public function mealItems(): HasMany
    {
        return $this->hasMany(MealItem::class);
    }

    /**
     * Get the nutritional information as a formatted string.
     */
    public function getNutritionalInfo(): string
    {
        return sprintf(
            '%dg prot · %dg carb · %dg gord · %dkcal',
            $this->protein,
            $this->carbohydrate,
            $this->fat,
            $this->calories
        );
    }

    /**
     * Scope a query to only include foods for a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include favorite foods.
     */
    public function scopeFavorites(Builder $query): Builder
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Scope a query to search foods by name.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Scope a query to filter by source.
     */
    public function scopeBySource(Builder $query, string $source): Builder
    {
        return $query->where('source', $source);
    }

    /**
     * Scope a query to filter foods by barcode.
     */
    public function scopeByBarcode(Builder $query, string $barcode): Builder
    {
        return $query->where('barcode', $barcode);
    }
}
