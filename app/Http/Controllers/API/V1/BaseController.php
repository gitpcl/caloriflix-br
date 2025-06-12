<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseController extends Controller
{
    /**
     * Send a success response.
     *
     * @param mixed $result
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($result, string $message = 'Operation successful', int $code = 200): JsonResponse
    {
        // Handle resources
        if ($result instanceof JsonResource || $result instanceof ResourceCollection) {
            return $result->additional([
                'success' => true,
                'message' => $message,
            ])->response()->setStatusCode($code);
        }

        // Handle paginated results
        if ($result instanceof LengthAwarePaginator) {
            $response = [
                'success' => true,
                'data' => $result->items(),
                'message' => $message,
                'meta' => [
                    'current_page' => $result->currentPage(),
                    'from' => $result->firstItem(),
                    'last_page' => $result->lastPage(),
                    'per_page' => $result->perPage(),
                    'to' => $result->lastItem(),
                    'total' => $result->total(),
                ],
                'links' => [
                    'first' => $result->url(1),
                    'last' => $result->url($result->lastPage()),
                    'prev' => $result->previousPageUrl(),
                    'next' => $result->nextPageUrl(),
                ],
            ];
        } else {
            $response = [
                'success' => true,
                'data' => $result,
                'message' => $message,
            ];
        }

        return response()->json($response, $code);
    }

    /**
     * Send an error response.
     *
     * @param string $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        // Add rate limit headers if available
        if ($code === 429) {
            $response['retry_after'] = request()->header('Retry-After');
        }

        return response()->json($response, $code);
    }

    /**
     * Send a created response.
     *
     * @param mixed $result
     * @param string $message
     * @return JsonResponse
     */
    public function sendCreated($result, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->sendResponse($result, $message, 201);
    }

    /**
     * Send a no content response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public function sendNoContent(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 204);
    }
}
