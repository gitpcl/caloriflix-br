<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'height' => $this->height,
            'weight' => $this->weight,
            'goal_weight' => $this->goal_weight,
            'activity_level' => $this->activity_level,
            'diet_type' => $this->diet_type,
            'allergies' => $this->allergies,
            'bio' => $this->bio,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}