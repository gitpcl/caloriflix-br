<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reminder extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'reminder_type',
        'interval_hours',
        'interval_minutes',
        'start_time',
        'end_time',
        'buttons_enabled',
        'auto_command_enabled',
        'auto_command',
        'active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'buttons_enabled' => 'boolean',
        'auto_command_enabled' => 'boolean',
        'active' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    
    /**
     * Get the user that owns the reminder.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the details for the reminder.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ReminderDetail::class);
    }
}