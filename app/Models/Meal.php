<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_type',
        'meal_date',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mealItems()
    {
        return $this->hasMany(MealItem::class);
    }

    /**
     * Scope a query to only include meals for a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter meals by date range.
     */
    public function scopeDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('meal_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter meals by specific date.
     */
    public function scopeByDate(Builder $query, string $date): Builder
    {
        return $query->where('meal_date', $date);
    }

    /**
     * Scope a query to filter meals by type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('meal_type', $type);
    }

    /**
     * Scope a query to get today's meals.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->where('meal_date', today()->format('Y-m-d'));
    }
}
