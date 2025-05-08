<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class UserProfile extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'weight',
        'height',
        'gender',
        'age',
        'activity_level',
        'basal_metabolic_rate',
        'use_basal_metabolic_rate',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weight' => 'decimal:1',
        'height' => 'integer',
        'age' => 'integer',
        'basal_metabolic_rate' => 'integer',
        'use_basal_metabolic_rate' => 'boolean',
    ];
    
    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
