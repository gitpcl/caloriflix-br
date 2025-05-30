<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class NutritionalGoal extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'protein',
        'carbs',
        'fat',
        'fiber',
        'calories',
        'water',
        'objective',
        'start_date',
        'end_date',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'protein' => 'integer',
        'carbs' => 'integer',
        'fat' => 'integer',
        'fiber' => 'integer',
        'calories' => 'integer',
        'water' => 'integer',
    ];
    
    /**
     * Get the user that owns the nutritional goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
