<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
