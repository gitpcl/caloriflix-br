<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReminderDetail extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reminder_id',
        'button_text',
        'button_action',
        'display_order',
    ];
    
    /**
     * Get the reminder that owns the detail.
     */
    public function reminder(): BelongsTo
    {
        return $this->belongsTo(Reminder::class);
    }
}