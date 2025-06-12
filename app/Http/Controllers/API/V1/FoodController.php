<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Food;
use App\Http\Resources\V1\FoodResource;
use App\Http\Requests\V1\StoreFoodRequest;
use App\Http\Requests\V1\UpdateFoodRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodController extends BaseController
{
    /**
     * Display a listing of foods.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Food::forUser($request->user()->id);
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Apply source filter if provided
        if ($request->has('source') && in_array($request->source, ['manual', 'whatsapp'])) {
            $query->bySource($request->source);
        }
        
        // Apply favorite filter
        if ($request->boolean('favorite')) {
            $query->favorites();
        }
        
        // Apply sorting
        $sortField = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        if (in_array($sortField, ['name', 'calories', 'protein', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $foods = $query->paginate($request->get('per_page', 15));
        
        return $this->sendResponse(
            FoodResource::collection($foods)->response()->getData(true),
            'Foods retrieved successfully'
        );
    }

    /**
     * Store a newly created food.
     *
     * @param StoreFoodRequest $request
     * @return JsonResponse
     */
    public function store(StoreFoodRequest $request): JsonResponse
    {
        $food = Food::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'protein' => $request->protein,
            'fat' => $request->fat,
            'carbohydrate' => $request->carbohydrate,
            'fiber' => $request->fiber ?? 0,
            'calories' => $request->calories,
            'barcode' => $request->barcode,
            'is_favorite' => $request->is_favorite ?? false,
            'source' => 'manual',
        ]);

        return $this->sendCreated(new FoodResource($food), 'Food created successfully');
    }

    /**
     * Display the specified food.
     *
     * @param Food $food
     * @return JsonResponse
     */
    public function show(Food $food): JsonResponse
    {
        if ($food->user_id !== request()->user()->id) {
            return $this->sendError('Forbidden.', [], 403);
        }

        return $this->sendResponse(new FoodResource($food), 'Food retrieved successfully');
    }

    /**
     * Update the specified food.
     *
     * @param UpdateFoodRequest $request
     * @param Food $food
     * @return JsonResponse
     */
    public function update(UpdateFoodRequest $request, Food $food): JsonResponse
    {
        $food->update($request->validated());

        return $this->sendResponse(new FoodResource($food), 'Food updated successfully');
    }

    /**
     * Remove the specified food.
     *
     * @param Food $food
     * @return JsonResponse
     */
    public function destroy(Food $food): JsonResponse
    {
        if ($food->user_id !== request()->user()->id) {
            return $this->sendError('Forbidden.', [], 403);
        }

        $food->delete();

        return $this->sendNoContent('Food deleted successfully');
    }

    /**
     * Get favorite foods.
     *
     * @return JsonResponse
     */
    public function favorites(): JsonResponse
    {
        $favorites = Food::forUser(request()->user()->id)
            ->favorites()
            ->orderBy('name')
            ->get();

        return $this->sendResponse(FoodResource::collection($favorites), 'Favorite foods retrieved successfully');
    }
}
