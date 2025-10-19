<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationApiController extends Controller
{
    /**
     * Get all notifications for the authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (! $user) {
                Log::warning('Unauthenticated request to notifications API');

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            Log::info('Fetching notifications for user: '.$user->id);

            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            // Process notifications to ensure consistent data format
            $processedNotifications = $notifications->map(function ($notification) {
                // Ensure data is always an array
                $data = $notification->data;
                if (is_string($data)) {
                    $data = json_decode($data, true) ?: [];
                }

                // Extract message from data if it exists
                $message = null;
                if (isset($data['message'])) {
                    $message = $data['message'];
                }

                // Handle case where message might be in the notification itself
                if (! $message && isset($notification->message)) {
                    $message = $notification->message;
                }

                return [
                    'id' => $notification->id,
                    'user_id' => $notification->user_id,
                    'type' => $notification->type,
                    'message' => $message,
                    'data' => $data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                    'updated_at' => $notification->updated_at,
                ];
            });

            Log::info('Found '.$notifications->count().' notifications for user: '.$user->id);

            return response()->json([
                'success' => true,
                'items' => $processedNotifications,
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching notifications: '.$e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching notifications',
            ], 500);
        }
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        try {
            $user = $request->user();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $notification = Notification::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (! $notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: '.$e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id,
                'notification_id' => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read',
            ], 500);
        }
    }
}
