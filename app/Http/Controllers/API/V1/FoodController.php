<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Food;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $query = Food::query()->where('user_id', request()->user()->id);
        
        // Apply search filter if provided
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Apply source filter if provided
        if ($request->has('source') && in_array($request->source, ['manual', 'whatsapp'])) {
            $query->where('source', $request->source);
        }
        
        // Apply favorite filter
        if ($request->has('favorite') && $request->favorite) {
            $query->where('is_favorite', true);
        }
        
        // Apply sorting
        $sortField = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        if (in_array($sortField, ['name', 'calories', 'protein', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $foods = $query->paginate($request->get('per_page', 15));
        
        return $this->sendResponse($foods, 'Foods retrieved successfully');
    }

    /**
     * Store a newly created food.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'protein' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
            'carbohydrate' => 'required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'calories' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|max:100',
            'is_favorite' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $food = Food::create([
            'user_id' => request()->user()->id,
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

        return $this->sendResponse($food, 'Food created successfully');
    }

    /**
     * Display the specified food.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $food = Food::where('user_id', request()->user()->id)->find($id);

        if (is_null($food)) {
            return $this->sendError('Food not found.');
        }

        return $this->sendResponse($food, 'Food retrieved successfully');
    }

    /**
     * Update the specified food.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $food = Food::where('user_id', request()->user()->id)->find($id);

        if (is_null($food)) {
            return $this->sendError('Food not found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'quantity' => 'sometimes|required|numeric|min:0',
            'unit' => 'sometimes|required|string|max:50',
            'protein' => 'sometimes|required|numeric|min:0',
            'fat' => 'sometimes|required|numeric|min:0',
            'carbohydrate' => 'sometimes|required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'calories' => 'sometimes|required|numeric|min:0',
            'barcode' => 'nullable|string|max:100',
            'is_favorite' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $food->update($request->all());

        return $this->sendResponse($food, 'Food updated successfully');
    }

    /**
     * Remove the specified food.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $food = Food::where('user_id', request()->user()->id)->find($id);

        if (is_null($food)) {
            return $this->sendError('Food not found.');
        }

        $food->delete();

        return $this->sendResponse(null, 'Food deleted successfully');
    }

    /**
     * Get favorite foods.
     *
     * @return JsonResponse
     */
    public function favorites(): JsonResponse
    {
        $favorites = Food::where('user_id', request()->user()->id)
            ->where('is_favorite', true)
            ->orderBy('name')
            ->get();

        return $this->sendResponse($favorites, 'Favorite foods retrieved successfully');
    }
}
