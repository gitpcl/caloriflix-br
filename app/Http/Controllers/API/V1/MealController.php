<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Meal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MealController extends BaseController
{
    /**
     * Display a listing of meals.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Meal::with(['mealItems.food'])
            ->where('user_id', request()->user()->id);
        
        // Apply date range filter if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        // Apply meal type filter if provided
        if ($request->has('type') && in_array($request->type, ['breakfast', 'lunch', 'dinner', 'snack'])) {
            $query->where('type', $request->type);
        }
        
        // Apply sorting
        $sortField = $request->get('sort_by', 'date');
        $sortDirection = $request->get('sort_direction', 'desc');
        if (in_array($sortField, ['date', 'type', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $meals = $query->paginate($request->get('per_page', 15));
        
        return $this->sendResponse($meals, 'Meals retrieved successfully');
    }

    /**
     * Store a newly created meal.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'type' => 'required|string|in:breakfast,lunch,dinner,snack',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $meal = Meal::create([
            'user_id' => request()->user()->id,
            'date' => $request->date,
            'type' => $request->type,
            'note' => $request->note,
        ]);

        return $this->sendResponse($meal, 'Meal created successfully');
    }

    /**
     * Display the specified meal with its meal items.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $meal = Meal::with(['mealItems.food'])
            ->where('user_id', request()->user()->id)
            ->find($id);

        if (is_null($meal)) {
            return $this->sendError('Meal not found.');
        }

        return $this->sendResponse($meal, 'Meal retrieved successfully');
    }

    /**
     * Update the specified meal.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $meal = Meal::where('user_id', request()->user()->id)->find($id);

        if (is_null($meal)) {
            return $this->sendError('Meal not found.');
        }

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|required|date',
            'type' => 'sometimes|required|string|in:breakfast,lunch,dinner,snack',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $meal->update($request->all());

        return $this->sendResponse($meal, 'Meal updated successfully');
    }

    /**
     * Remove the specified meal.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $meal = Meal::where('user_id', request()->user()->id)->find($id);

        if (is_null($meal)) {
            return $this->sendError('Meal not found.');
        }

        // Delete associated meal items
        $meal->mealItems()->delete();
        
        // Delete the meal
        $meal->delete();

        return $this->sendResponse(null, 'Meal deleted successfully');
    }

    /**
     * Get today's meals.
     *
     * @return JsonResponse
     */
    public function today(): JsonResponse
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $meals = Meal::with(['mealItems.food'])
            ->where('user_id', request()->user()->id)
            ->where('date', $today)
            ->orderBy('type')
            ->get();

        return $this->sendResponse($meals, 'Today\'s meals retrieved successfully');
    }

    /**
     * Get meals by specific date.
     *
     * @param string $date
     * @return JsonResponse
     */
    public function byDate($date): JsonResponse
    {
        // Validate date format
        if (!Carbon::hasFormat($date, 'Y-m-d')) {
            return $this->sendError('Invalid date format. Use YYYY-MM-DD format.');
        }
        
        $meals = Meal::with(['mealItems.food'])
            ->where('user_id', request()->user()->id)
            ->where('date', $date)
            ->orderBy('type')
            ->get();

        return $this->sendResponse($meals, 'Meals for ' . $date . ' retrieved successfully');
    }
}
