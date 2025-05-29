<?php

namespace App\Http\Controllers\API\V1;

use App\Models\NutritionalGoal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NutritionalGoalController extends BaseController
{
    /**
     * Display a listing of the user's nutritional goals.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $goals = NutritionalGoal::where('user_id', request()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->sendResponse($goals, 'Nutritional goals retrieved successfully');
    }

    /**
     * Store a newly created nutritional goal.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'calories' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
            'carbohydrate' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'water' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // If is_active is true, deactivate all other goals
        if ($request->is_active) {
            NutritionalGoal::where('user_id', request()->user()->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $nutritionalGoal = NutritionalGoal::create([
            'user_id' => request()->user()->id,
            'calories' => $request->calories,
            'protein' => $request->protein,
            'carbohydrate' => $request->carbohydrate,
            'fat' => $request->fat,
            'fiber' => $request->fiber ?? 0,
            'water' => $request->water ?? 2000, // Default to 2L if not provided
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? false,
        ]);

        return $this->sendResponse($nutritionalGoal, 'Nutritional goal created successfully');
    }

    /**
     * Display the specified nutritional goal.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $nutritionalGoal = NutritionalGoal::where('user_id', request()->user()->id)->find($id);

        if (is_null($nutritionalGoal)) {
            return $this->sendError('Nutritional goal not found.');
        }

        return $this->sendResponse($nutritionalGoal, 'Nutritional goal retrieved successfully');
    }

    /**
     * Update the specified nutritional goal.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $nutritionalGoal = NutritionalGoal::where('user_id', request()->user()->id)->find($id);

        if (is_null($nutritionalGoal)) {
            return $this->sendError('Nutritional goal not found.');
        }

        $validator = Validator::make($request->all(), [
            'calories' => 'sometimes|required|numeric|min:0',
            'protein' => 'sometimes|required|numeric|min:0',
            'carbohydrate' => 'sometimes|required|numeric|min:0',
            'fat' => 'sometimes|required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'water' => 'nullable|numeric|min:0',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // If is_active is being set to true, deactivate all other goals
        if ($request->has('is_active') && $request->is_active && !$nutritionalGoal->is_active) {
            NutritionalGoal::where('user_id', request()->user()->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $nutritionalGoal->update($request->all());

        return $this->sendResponse($nutritionalGoal, 'Nutritional goal updated successfully');
    }

    /**
     * Remove the specified nutritional goal.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $nutritionalGoal = NutritionalGoal::where('user_id', request()->user()->id)->find($id);

        if (is_null($nutritionalGoal)) {
            return $this->sendError('Nutritional goal not found.');
        }

        $nutritionalGoal->delete();

        return $this->sendResponse(null, 'Nutritional goal deleted successfully');
    }

    /**
     * Get the user's current active nutritional goal.
     *
     * @return JsonResponse
     */
    public function current(): JsonResponse
    {
        $currentGoal = NutritionalGoal::where('user_id', request()->user()->id)
            ->where('is_active', true)
            ->first();

        if (!$currentGoal) {
            // Fallback to most recent goal
            $currentGoal = NutritionalGoal::where('user_id', request()->user()->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        if (!$currentGoal) {
            return $this->sendError('No nutritional goal found.');
        }

        return $this->sendResponse($currentGoal, 'Current nutritional goal retrieved successfully');
    }
}
