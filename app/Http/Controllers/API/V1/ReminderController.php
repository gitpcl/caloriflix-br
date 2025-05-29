<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Reminder;
use App\Models\ReminderDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReminderController extends BaseController
{
    /**
     * Display a listing of reminders.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Reminder::with('reminderDetails')
            ->where('user_id', request()->user()->id);
        
        // Apply type filter if provided
        if ($request->has('type') && in_array($request->type, ['meal', 'water', 'medication', 'measurement', 'other'])) {
            $query->where('type', $request->type);
        }
        
        // Filter by active status
        if ($request->has('active')) {
            $active = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
            $query->where('active', $active);
        }
        
        // Apply sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        if (in_array($sortField, ['title', 'type', 'time', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $reminders = $query->paginate($request->get('per_page', 15));
        
        return $this->sendResponse($reminders, 'Reminders retrieved successfully');
    }

    /**
     * Store a newly created reminder.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:meal,water,medication,measurement,other',
            'time' => 'required|date_format:H:i',
            'days' => 'required|array',
            'days.*' => 'required|integer|between:0,6', // 0 = Sunday, 6 = Saturday
            'active' => 'nullable|boolean',
            'repeat_type' => 'required|string|in:daily,weekly,monthly,specific_days',
            'details' => 'nullable|array',
            'details.*.content' => 'required|string',
            'details.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $reminder = Reminder::create([
            'user_id' => request()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'time' => $request->time,
            'days' => json_encode($request->days),
            'active' => $request->active ?? true,
            'repeat_type' => $request->repeat_type,
        ]);

        // Create reminder details if provided
        if ($request->has('details') && is_array($request->details)) {
            foreach ($request->details as $detail) {
                ReminderDetail::create([
                    'reminder_id' => $reminder->id,
                    'content' => $detail['content'],
                    'order' => $detail['order'],
                ]);
            }
        }

        // Load the reminder details
        $reminder->load('reminderDetails');

        return $this->sendResponse($reminder, 'Reminder created successfully');
    }

    /**
     * Display the specified reminder.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $reminder = Reminder::with('reminderDetails')
            ->where('user_id', request()->user()->id)
            ->find($id);

        if (is_null($reminder)) {
            return $this->sendError('Reminder not found.');
        }

        return $this->sendResponse($reminder, 'Reminder retrieved successfully');
    }

    /**
     * Update the specified reminder.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $reminder = Reminder::where('user_id', request()->user()->id)->find($id);

        if (is_null($reminder)) {
            return $this->sendError('Reminder not found.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|string|in:meal,water,medication,measurement,other',
            'time' => 'sometimes|required|date_format:H:i',
            'days' => 'sometimes|required|array',
            'days.*' => 'required|integer|between:0,6', // 0 = Sunday, 6 = Saturday
            'active' => 'nullable|boolean',
            'repeat_type' => 'sometimes|required|string|in:daily,weekly,monthly,specific_days',
            'details' => 'nullable|array',
            'details.*.id' => 'nullable|integer|exists:reminder_details,id',
            'details.*.content' => 'required|string',
            'details.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // Update days as JSON if provided
        if ($request->has('days')) {
            $request->merge(['days' => json_encode($request->days)]);
        }

        $reminder->update($request->except('details'));

        // Update or create reminder details if provided
        if ($request->has('details') && is_array($request->details)) {
            $existingIds = [];
            
            foreach ($request->details as $detail) {
                if (isset($detail['id'])) {
                    // Update existing detail
                    $reminderDetail = ReminderDetail::where('reminder_id', $reminder->id)
                        ->find($detail['id']);
                        
                    if ($reminderDetail) {
                        $reminderDetail->update([
                            'content' => $detail['content'],
                            'order' => $detail['order'],
                        ]);
                        
                        $existingIds[] = $detail['id'];
                    }
                } else {
                    // Create new detail
                    $newDetail = ReminderDetail::create([
                        'reminder_id' => $reminder->id,
                        'content' => $detail['content'],
                        'order' => $detail['order'],
                    ]);
                    
                    $existingIds[] = $newDetail->id;
                }
            }
            
            // Delete any details that weren't in the request
            ReminderDetail::where('reminder_id', $reminder->id)
                ->whereNotIn('id', $existingIds)
                ->delete();
        }

        // Load the updated reminder details
        $reminder->load('reminderDetails');

        return $this->sendResponse($reminder, 'Reminder updated successfully');
    }

    /**
     * Remove the specified reminder.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $reminder = Reminder::where('user_id', request()->user()->id)->find($id);

        if (is_null($reminder)) {
            return $this->sendError('Reminder not found.');
        }

        // Delete associated reminder details
        $reminder->reminderDetails()->delete();
        
        // Delete the reminder
        $reminder->delete();

        return $this->sendResponse(null, 'Reminder deleted successfully');
    }
}
