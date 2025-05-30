<?php

namespace App\Http\Controllers\API\V1;

use App\Models\MealItem;
use App\Models\Meal;
use App\Models\Food;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MealItemController extends BaseController
{
    /**
     * Display a listing of meal items.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // We should only return meal items that belong to the user's meals
        $mealItems = MealItem::with(['meal', 'food'])
            ->whereHas('meal', function($query) {
                $query->where('user_id', request()->user()->id);
            });
            
        if ($request->has('meal_id')) {
            $mealItems->where('meal_id', $request->meal_id);
        }
        
        return $this->sendResponse(
            $mealItems->paginate($request->get('per_page', 15)), 
            'Meal items retrieved successfully'
        );
    }

    /**
     * Store a newly created meal item.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'meal_id' => 'required|integer|exists:meals,id',
            'food_id' => 'required|integer|exists:foods,id',
            'quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Ensure the meal belongs to the authenticated user
        $meal = Meal::where('id', $request->meal_id)
            ->where('user_id', request()->user()->id)
            ->first();

        if (!$meal) {
            return $this->sendError('Unauthorized. This meal does not belong to you.', [], 403);
        }

        // Get the food item (can be any user's food since they might be shared)
        $food = Food::find($request->food_id);

        $mealItem = MealItem::create([
            'meal_id' => $request->meal_id,
            'food_id' => $request->food_id,
            'quantity' => $request->quantity,
            'notes' => $request->notes,
        ]);

        // Load the relationships for the response
        $mealItem->load(['meal', 'food']);

        return response()->json([
            'success' => true,
            'data' => $mealItem,
            'message' => 'Meal item created successfully'
        ], 201);
    }

    /**
     * Display the specified meal item.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $mealItem = MealItem::with(['meal', 'food'])->find($id);

        if (is_null($mealItem)) {
            return $this->sendError('Meal item not found.');
        }

        if ($mealItem->meal->user_id !== request()->user()->id) {
            return $this->sendError('Forbidden.', [], 403);
        }

        return $this->sendResponse($mealItem, 'Meal item retrieved successfully');
    }

    /**
     * Update the specified meal item.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $mealItem = MealItem::with('meal')->find($id);

        if (is_null($mealItem)) {
            return $this->sendError('Meal item not found.');
        }

        if ($mealItem->meal->user_id !== request()->user()->id) {
            return $this->sendError('Forbidden.', [], 403);
        }

        $validator = Validator::make($request->all(), [
            'meal_id' => 'sometimes|integer|exists:meals,id',
            'food_id' => 'sometimes|integer|exists:foods,id',
            'quantity' => 'sometimes|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // If meal_id is provided, ensure it belongs to the authenticated user
        if ($request->has('meal_id')) {
            $meal = Meal::where('id', $request->meal_id)
                ->where('user_id', request()->user()->id)
                ->first();

            if (!$meal) {
                return $this->sendError('Unauthorized. This meal does not belong to you.', [], 403);
            }
        }

        $mealItem->update($request->all());
        
        // Load the relationships for the response
        $mealItem->load(['meal', 'food']);

        return $this->sendResponse($mealItem, 'Meal item updated successfully');
    }

    /**
     * Remove the specified meal item.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $mealItem = MealItem::with('meal')->find($id);

        if (is_null($mealItem)) {
            return $this->sendError('Meal item not found.');
        }

        if ($mealItem->meal->user_id !== request()->user()->id) {
            return $this->sendError('Forbidden.', [], 403);
        }

        $mealItem->delete();

        return $this->sendResponse(null, 'Meal item deleted successfully');
    }
}
