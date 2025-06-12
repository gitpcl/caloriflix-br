<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class ApiHandler
{
    /**
     * Render an exception into an HTTP response for API requests.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     */
    public function render(Request $request, Throwable $exception): JsonResponse
    {
        // Handle validation errors
        if ($exception instanceof ValidationException) {
            return $this->handleValidationException($exception);
        }

        // Handle authentication errors
        if ($exception instanceof AuthenticationException) {
            return $this->handleAuthenticationException($exception);
        }

        // Handle model not found errors
        if ($exception instanceof ModelNotFoundException) {
            return $this->handleModelNotFoundException($exception);
        }

        // Handle 404 errors
        if ($exception instanceof NotFoundHttpException) {
            return $this->handleNotFoundHttpException($exception);
        }

        // Handle method not allowed errors
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->handleMethodNotAllowedHttpException($exception);
        }

        // Handle rate limiting errors
        if ($exception instanceof TooManyRequestsHttpException) {
            return $this->handleTooManyRequestsHttpException($exception);
        }

        // Handle other HTTP exceptions
        if ($exception instanceof HttpException) {
            return $this->handleHttpException($exception);
        }

        // Handle general exceptions
        return $this->handleGeneralException($exception);
    }

    /**
     * Handle validation exceptions.
     *
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function handleValidationException(ValidationException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation Error.',
            'errors' => $exception->errors(),
        ], 422);
    }

    /**
     * Handle authentication exceptions.
     *
     * @param AuthenticationException $exception
     * @return JsonResponse
     */
    protected function handleAuthenticationException(AuthenticationException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated.',
            'error' => 'Please log in to access this resource.',
        ], 401);
    }

    /**
     * Handle model not found exceptions.
     *
     * @param ModelNotFoundException $exception
     * @return JsonResponse
     */
    protected function handleModelNotFoundException(ModelNotFoundException $exception): JsonResponse
    {
        $model = class_basename($exception->getModel());
        
        return response()->json([
            'success' => false,
            'message' => "Resource not found.",
            'error' => "{$model} not found.",
        ], 404);
    }

    /**
     * Handle not found HTTP exceptions.
     *
     * @param NotFoundHttpException $exception
     * @return JsonResponse
     */
    protected function handleNotFoundHttpException(NotFoundHttpException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Endpoint not found.',
            'error' => 'The requested resource could not be found.',
        ], 404);
    }

    /**
     * Handle method not allowed HTTP exceptions.
     *
     * @param MethodNotAllowedHttpException $exception
     * @return JsonResponse
     */
    protected function handleMethodNotAllowedHttpException(MethodNotAllowedHttpException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Method not allowed.',
            'error' => 'The HTTP method is not allowed for this endpoint.',
            'allowed_methods' => $exception->getHeaders()['Allow'] ?? [],
        ], 405);
    }

    /**
     * Handle too many requests HTTP exceptions.
     *
     * @param TooManyRequestsHttpException $exception
     * @return JsonResponse
     */
    protected function handleTooManyRequestsHttpException(TooManyRequestsHttpException $exception): JsonResponse
    {
        $retryAfter = $exception->getHeaders()['Retry-After'] ?? null;
        
        return response()->json([
            'success' => false,
            'message' => 'Too many requests.',
            'error' => 'Rate limit exceeded. Please try again later.',
            'retry_after' => $retryAfter,
        ], 429)->withHeaders($exception->getHeaders());
    }

    /**
     * Handle general HTTP exceptions.
     *
     * @param HttpException $exception
     * @return JsonResponse
     */
    protected function handleHttpException(HttpException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'HTTP Error.',
            'error' => $exception->getMessage() ?: 'An HTTP error occurred.',
        ], $exception->getStatusCode());
    }

    /**
     * Handle general exceptions.
     *
     * @param Throwable $exception
     * @return JsonResponse
     */
    protected function handleGeneralException(Throwable $exception): JsonResponse
    {
        // Log the exception for debugging
        logger()->error('API Exception: ' . $exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString(),
        ]);

        // Return a generic error in production, detailed error in development
        $message = app()->hasDebugModeEnabled() 
            ? $exception->getMessage() 
            : 'Internal server error.';

        $response = [
            'success' => false,
            'message' => 'Server Error.',
            'error' => $message,
        ];

        // Add debug information if in debug mode
        if (app()->hasDebugModeEnabled()) {
            $response['debug'] = [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        return response()->json($response, 500);
    }
}