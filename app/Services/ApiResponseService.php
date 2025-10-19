<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponseService
{
    public function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    public function notFound(string $message = 'Not Found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    public function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, 401);
    }

    public function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, 403);
    }

    public function validationError($errors, string $message = 'Validation Error'): JsonResponse
    {
        return $this->error($message, 422, $errors);
    }

    public function paginated(LengthAwarePaginator $paginator, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ]);
    }

    public function created($data = null, string $message = 'Created'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    public function noContent(string $message = 'No Content'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 204);
    }

    public function withMeta($data, array $meta, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ]);
    }
}
