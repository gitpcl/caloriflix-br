<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
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
            'date' => $this->date,
            'type' => $this->type,
            'note' => $this->note,
            'meal_items' => MealItemResource::collection($this->whenLoaded('mealItems')),
            'total_calories' => $this->when($this->relationLoaded('mealItems'), function () {
                return $this->mealItems->sum(function ($item) {
                    return ($item->food->calories / $item->food->quantity) * $item->quantity;
                });
            }),
            'total_macros' => $this->when($this->relationLoaded('mealItems'), function () {
                return [
                    'protein' => $this->mealItems->sum(function ($item) {
                        return ($item->food->protein / $item->food->quantity) * $item->quantity;
                    }),
                    'fat' => $this->mealItems->sum(function ($item) {
                        return ($item->food->fat / $item->food->quantity) * $item->quantity;
                    }),
                    'carbohydrate' => $this->mealItems->sum(function ($item) {
                        return ($item->food->carbohydrate / $item->food->quantity) * $item->quantity;
                    }),
                    'fiber' => $this->mealItems->sum(function ($item) {
                        return ($item->food->fiber / $item->food->quantity) * $item->quantity;
                    }),
                ];
            }),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}