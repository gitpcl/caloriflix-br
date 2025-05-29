<?php

namespace App\Http\Controllers\API\V1;

use App\Models\UserPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserPreferenceController extends BaseController
{
    /**
     * Display the authenticated user's preferences.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $preferences = UserPreference::where('user_id', request()->user()->id)->first();
        
        if (!$preferences) {
            $preferences = UserPreference::create([
                'user_id' => request()->user()->id,
                'theme' => 'dark',
                'language' => 'pt',
                'notifications_enabled' => true,
                'email_notifications' => false,
            ]);
        }
        
        return $this->sendResponse($preferences, 'User preferences retrieved successfully');
    }

    /**
     * Update the authenticated user's preferences.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $preferences = UserPreference::where('user_id', request()->user()->id)->first();
        
        if (!$preferences) {
            $preferences = new UserPreference(['user_id' => request()->user()->id]);
        }
        
        $validator = Validator::make($request->all(), [
            'theme' => 'nullable|string|in:light,dark',
            'language' => 'nullable|string|size:2',
            'notifications_enabled' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
            'weekly_report' => 'nullable|boolean',
            'meal_reminders' => 'nullable|boolean',
            'water_reminders' => 'nullable|boolean',
            'measurement_reminders' => 'nullable|boolean',
            'display_units' => 'nullable|string|in:metric,imperial',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        
        // Fill the preferences with the request data
        $preferences->fill($request->all());
        $preferences->save();
        
        return $this->sendResponse($preferences, 'User preferences updated successfully');
    }
}
