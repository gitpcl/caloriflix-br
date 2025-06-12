<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'theme' => $this->theme,
            'language' => $this->language,
            'notifications_enabled' => $this->notifications_enabled,
            'email_notifications' => $this->email_notifications,
            'weekly_report' => $this->weekly_report,
            'meal_reminders' => $this->meal_reminders,
            'water_reminders' => $this->water_reminders,
            'measurement_reminders' => $this->measurement_reminders,
            'display_units' => $this->display_units,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}