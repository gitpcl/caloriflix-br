<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class UserPlan extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'content',
        'file_path',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => 'string',
    ];
    
    /**
     * The attributes that should be appended to arrays.
     *
     * @var array
     */
    protected $appends = [];
    
    /**
     * Get the user that owns the plan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include diet plans.
     */
    public function scopeDiet($query)
    {
        return $query->where('type', 'diet');
    }
    
    /**
     * Scope a query to only include training plans.
     */
    public function scopeTraining($query)
    {
        return $query->where('type', 'training');
    }
}
