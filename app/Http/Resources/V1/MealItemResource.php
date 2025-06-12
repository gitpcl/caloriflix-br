<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealItemResource extends JsonResource
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
            'meal_id' => $this->meal_id,
            'food_id' => $this->food_id,
            'quantity' => $this->quantity,
            'notes' => $this->notes,
            'food' => new FoodResource($this->whenLoaded('food')),
            'calculated_nutrition' => $this->when($this->relationLoaded('food'), function () {
                $multiplier = $this->quantity / $this->food->quantity;
                return [
                    'calories' => round($this->food->calories * $multiplier, 2),
                    'protein' => round($this->food->protein * $multiplier, 2),
                    'fat' => round($this->food->fat * $multiplier, 2),
                    'carbohydrate' => round($this->food->carbohydrate * $multiplier, 2),
                    'fiber' => round($this->food->fiber * $multiplier, 2),
                ];
            }),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}