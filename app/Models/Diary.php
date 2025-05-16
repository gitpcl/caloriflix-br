<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'notes',
        'mood',
        'water',
        'sleep',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'mood' => 'integer',
        'water' => 'integer',
        'sleep' => 'integer',
    ];

    /**
     * Get the user that owns the diary entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Format sleep duration from minutes to hours and minutes.
     */
    public function getFormattedSleepAttribute(): string
    {
        if (!$this->sleep) {
            return '0h 0m';
        }

        $hours = floor($this->sleep / 60);
        $minutes = $this->sleep % 60;

        return "{$hours}h {$minutes}m";
    }

    /**
     * Get the mood emoji based on the mood level.
     */
    public function getMoodEmojiAttribute(): string
    {
        return match ($this->mood) {
            1 => '😞',
            2 => '😐',
            3 => '🙂',
            4 => '😊',
            5 => '😁',
            default => '❓',
        };
    }
}
