<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        // API requests
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $exception);
        }

        // Web requests
        return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions.
     */
    protected function handleApiException(Request $request, Throwable $exception): JsonResponse
    {
        // Validation exception
        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $exception->errors(),
            ], 422);
        }

        // Not found exception
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        // Unauthorized exception
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        // Access denied exception
        if ($exception instanceof AccessDeniedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        // General exception
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage() ?: 'An error occurred',
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ], 500);
    }
}
