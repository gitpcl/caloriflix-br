<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'name',
        'ingredients',
        'instructions',
        'preparation_time',
        'cooking_time',
        'servings',
        'protein',
        'fat',
        'carbohydrate',
        'fiber',
        'calories',
    ];
    
    /**
     * Get the user that owns the recipe.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the total time for the recipe (preparation + cooking).
     */
    public function getTotalTimeAttribute(): ?int
    {
        if ($this->preparation_time === null || $this->cooking_time === null) {
            return null;
        }
        
        return $this->preparation_time + $this->cooking_time;
    }
}
