<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\V1\TokenResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TokenController extends BaseController
{
    /**
     * List all active tokens for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $tokens = $request->user()->tokens()->where('expires_at', '>', now())->get();

        return $this->sendResponse(
            TokenResource::collection($tokens),
            'Active tokens retrieved successfully'
        );
    }

    /**
     * Create a new token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'abilities' => 'nullable|array',
            'abilities.*' => 'string|in:read,write,delete',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $abilities = $request->abilities ?? ['*'];
        $expiresAt = $request->expires_in_days 
            ? now()->addDays($request->expires_in_days)
            : now()->addDays(config('sanctum.expiration') / (60 * 24));

        $token = $request->user()->createToken(
            $request->name,
            $abilities,
            $expiresAt
        );

        return $this->sendCreated([
            'token' => $token->plainTextToken,
            'expires_at' => $expiresAt->toIso8601String(),
            'abilities' => $abilities,
        ], 'Token created successfully');
    }

    /**
     * Revoke a specific token.
     *
     * @param Request $request
     * @param int $tokenId
     * @return JsonResponse
     */
    public function destroy(Request $request, $tokenId): JsonResponse
    {
        $token = $request->user()->tokens()->find($tokenId);

        if (!$token) {
            return $this->sendError('Token not found.', [], 404);
        }

        $token->delete();

        return $this->sendNoContent('Token revoked successfully');
    }

    /**
     * Revoke all tokens except the current one.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function revokeAll(Request $request): JsonResponse
    {
        $currentTokenId = $request->user()->currentAccessToken()->id;
        
        $request->user()->tokens()
            ->where('id', '!=', $currentTokenId)
            ->delete();

        return $this->sendResponse(null, 'All other tokens revoked successfully');
    }

    /**
     * Refresh the current token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $currentToken = $request->user()->currentAccessToken();
        $tokenName = $currentToken->name;
        $abilities = $currentToken->abilities;
        
        // Delete the current token
        $currentToken->delete();
        
        // Create a new token with the same properties
        $expiresAt = now()->addDays(config('sanctum.expiration') / (60 * 24));
        $newToken = $request->user()->createToken($tokenName, $abilities, $expiresAt);

        return $this->sendResponse([
            'token' => $newToken->plainTextToken,
            'expires_at' => $expiresAt->toIso8601String(),
            'abilities' => $abilities,
        ], 'Token refreshed successfully');
    }
}