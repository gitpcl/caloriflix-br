<?php

namespace App\Http\Controllers\API\V1;

use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends BaseController
{
    /**
     * Display the authenticated user's profile.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $profile = UserProfile::where('user_id', request()->user()->id)->first();
        
        if (!$profile) {
            return $this->sendError('Profile not found.');
        }
        
        // Load the user with the profile
        $profile->load('user');
        
        return $this->sendResponse($profile, 'User profile retrieved successfully');
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $profile = UserProfile::where('user_id', request()->user()->id)->first();
        
        if (!$profile) {
            // Create a profile if it doesn't exist
            $profile = new UserProfile(['user_id' => request()->user()->id]);
        }
        
        $validator = Validator::make($request->all(), [
            'gender' => 'nullable|string|in:male,female,other',
            'birth_date' => 'nullable|date',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'goal_weight' => 'nullable|numeric|min:0',
            'activity_level' => 'nullable|string|in:sedentary,lightly_active,moderately_active,very_active,extra_active',
            'diet_type' => 'nullable|string|max:100',
            'allergies' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        
        // Fill the profile with the request data
        $profile->fill($request->all());
        $profile->save();
        
        // Load the user with the updated profile
        $profile->load('user');
        
        return $this->sendResponse($profile, 'User profile updated successfully');
    }
}
