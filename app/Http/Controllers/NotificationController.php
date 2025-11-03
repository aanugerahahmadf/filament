<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    /**
     * Display the notifications page
     */
    public function index(Request $request): View|RedirectResponse
    {
        // The route is already protected by auth middleware, but we'll check anyway
        $user = $request->user();

        if (!$user) {
            // This shouldn't happen due to middleware, but just in case
            return redirect()->route('login');
        }

        // We don't need to fetch notifications here since they'll be loaded via AJAX
        // The view will handle loading them through the API

        return view('notifications');
    }

    /**
     * Get all notifications for the authenticated user
     */
    public function getNotifications(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

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
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json(['success' => true, 'message' => 'Notification marked as read']);
    }

    /**
     * Mark a notification as unread
     */
    public function markAsUnread(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        $notification->markAsUnread();

        return response()->json(['success' => true, 'message' => 'Notification marked as unread']);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Notification deleted successfully']);
    }
}
