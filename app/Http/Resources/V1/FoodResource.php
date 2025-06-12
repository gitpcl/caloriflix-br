<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
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
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'macros' => [
                'protein' => $this->protein,
                'fat' => $this->fat,
                'carbohydrate' => $this->carbohydrate,
                'fiber' => $this->fiber,
            ],
            'calories' => $this->calories,
            'barcode' => $this->barcode,
            'is_favorite' => $this->is_favorite,
            'source' => $this->source,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}