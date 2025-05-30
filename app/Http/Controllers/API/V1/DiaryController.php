<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Diary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DiaryController extends BaseController
{
    /**
     * Display a listing of diary entries.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Diary::where('user_id', request()->user()->id);
        
        // Apply date range filter if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        // Apply sorting
        $sortField = $request->get('sort_by', 'date');
        $sortDirection = $request->get('sort_direction', 'desc');
        if (in_array($sortField, ['date', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $diaries = $query->paginate($request->get('per_page', 15));
        
        return $this->sendResponse($diaries, 'Diary entries retrieved successfully');
    }

    /**
     * Store a newly created diary entry.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'water' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'mood' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Check if an entry already exists for this date
        $existingEntry = Diary::where('user_id', request()->user()->id)
            ->where('date', $request->date)
            ->first();
            
        if ($existingEntry) {
            return $this->sendError('An entry for this date already exists. Use update instead.', [], 422);
        }

        $diary = Diary::create([
            'user_id' => request()->user()->id,
            'date' => $request->date,
            'water' => $request->water ?? 0,
            'notes' => $request->notes,
            'mood' => $request->mood,
        ]);

        return $this->sendResponse($diary, 'Diary entry created successfully');
    }

    /**
     * Display the specified diary entry.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $diary = Diary::where('user_id', request()->user()->id)->find($id);

        if (is_null($diary)) {
            return $this->sendError('Diary entry not found.');
        }

        return $this->sendResponse($diary, 'Diary entry retrieved successfully');
    }

    /**
     * Update the specified diary entry.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $diary = Diary::where('user_id', request()->user()->id)->find($id);

        if (is_null($diary)) {
            return $this->sendError('Diary entry not found.');
        }

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|required|date',
            'water' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'mood' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // If date is being changed, check for conflicts
        if ($request->has('date') && $request->date != $diary->date) {
            $existingEntry = Diary::where('user_id', request()->user()->id)
                ->where('date', $request->date)
                ->first();
                
            if ($existingEntry) {
                return $this->sendError('An entry for this date already exists.', [], 422);
            }
        }

        $diary->update($request->all());

        return $this->sendResponse($diary, 'Diary entry updated successfully');
    }

    /**
     * Remove the specified diary entry.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $diary = Diary::where('user_id', request()->user()->id)->find($id);

        if (is_null($diary)) {
            return $this->sendError('Diary entry not found.');
        }

        $diary->delete();

        return $this->sendResponse(null, 'Diary entry deleted successfully');
    }

    /**
     * Get diary entry by date.
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
        
        $diary = Diary::where('user_id', request()->user()->id)
            ->whereDate('date', $date)
            ->first();

        if (!$diary) {
            return $this->sendError('No diary entry found for this date.', [], 404);
        }

        return $this->sendResponse($diary, 'Diary entry for ' . $date . ' retrieved successfully');
    }
}
