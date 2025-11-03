<?php

namespace App\Http\Controllers;

use App\Events\MessageDelivered;
use App\Events\MessageReadReceipt;
use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Message;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display the messages list page.
     */
    public function index()
    {
        return view('messages.messages-list');
    }

    /**
     * Store a newly created message.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_user_id' => 'required|exists:users,id',
            'body' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,csv,json|max:20480',
            'video' => 'nullable|mimetypes:video/mp4,video/webm,video/quicktime|max:51200',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ensure at least text or attachment is provided
        if (!$request->filled('body') && !$request->hasFile('image') && !$request->hasFile('file') && !$request->hasFile('video')) {
            $error = ['body' => ['Message text or attachment is required.']];
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $error], 422);
            }
            return redirect()->back()->withErrors($error)->withInput();
        }

        $payload = [
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'body' => $request->input('body'),
            'subject' => 'Message',
            'type' => 'message',
            'priority' => 'normal',
            'delivered_at' => now(),
        ];

        // Handle optional image upload
        if ($request->hasFile('image')) {
            try {
                $path = $request->file('image')->store('messages/images', 'public');
                $payload['message_type'] = 'image';
                $payload['attachment_path'] = $path;
                $payload['attachment_name'] = $request->file('image')->getClientOriginalName();
                $payload['attachment_size'] = $request->file('image')->getSize();
            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => ['image' => ['Failed to upload image.']]], 500);
                }
                return redirect()->back()->withErrors(['image' => 'Failed to upload image.'])->withInput();
            }
        }

        // Handle optional document/file upload
        if ($request->hasFile('file')) {
            try {
                $path = $request->file('file')->store('messages/files', 'public');
                $payload['message_type'] = 'file';
                $payload['attachment_path'] = $path;
                $payload['attachment_name'] = $request->file('file')->getClientOriginalName();
                $payload['attachment_size'] = $request->file('file')->getSize();
            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => ['file' => ['Failed to upload file.']]], 500);
                }
                return redirect()->back()->withErrors(['file' => 'Failed to upload file.'])->withInput();
            }
        }

        // Handle optional video upload
        if ($request->hasFile('video')) {
            try {
                $path = $request->file('video')->store('messages/videos', 'public');
                $payload['message_type'] = 'video';
                $payload['attachment_path'] = $path;
                $payload['attachment_name'] = $request->file('video')->getClientOriginalName();
                $payload['attachment_size'] = $request->file('video')->getSize();
            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => ['video' => ['Failed to upload video.']]], 500);
                }
                return redirect()->back()->withErrors(['video' => 'Failed to upload video.'])->withInput();
            }
        }

        try {
            $message = Message::create($payload);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => ['general' => ['Failed to save message.']]], 500);
            }
            return redirect()->back()->withErrors(['general' => 'Failed to save message.'])->withInput();
        }

        // Create notification for the recipient
        $recipient = User::find($request->to_user_id);
        $sender = Auth::user();
        if ($recipient && $sender) {
            try {
                $this->notificationService->sendMessageNotification($recipient, $sender, $request->body);
            } catch (\Exception $e) {
                // Log the error but don't fail the message sending
                Log::error('Failed to create notification: ' . $e->getMessage());
            }
        }

        // Broadcast events
        try {
            broadcast(new MessageSent($message))->toOthers();
            broadcast(new MessageDelivered($message))->toOthers();
        } catch (\Exception $e) {
            // Log the error but don't fail the message sending
            Log::error('Failed to broadcast message events: ' . $e->getMessage());
        }

        if ($request->ajax()) {
            // Include public URL if there is an attachment
            if (!empty($message->attachment_path)) {
                $message->attachment_url = url('/storage/' . str_replace('public/', '', $message->attachment_path));
            }
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    /**
     * Display the specified message conversation.
     */
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        return view('messages.messages-box', compact('user'));
    }

    /**
     * Show conversation between two users.
     */
    public function conversation($userId)
    {
        $user = User::findOrFail($userId);

        // Get messages between the authenticated user and the specified user
        $messages = Message::betweenUsers(Auth::id(), $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark received messages as read
        Message::where('from_user_id', $userId)
            ->where('to_user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Broadcast read receipt for the last message
        $lastMessage = Message::where('from_user_id', $userId)
            ->where('to_user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastMessage) {
            try {
                broadcast(new MessageReadReceipt($lastMessage))->toOthers();
            } catch (\Exception $e) {
                // Log the error but don't fail the operation
                Log::error('Failed to broadcast read receipt: ' . $e->getMessage());
            }
        }

        return view('messages.messages-box', compact('user', 'messages', 'userId'));
    }

    /**
     * Remove the specified message.
     */
    public function destroy(Message $message)
    {
        // Authorize that the user can delete this message
        if ($message->from_user_id !== Auth::id()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.']);
            }
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $message->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Message deleted successfully!']);
        }

        return redirect()->back()->with('success', 'Message deleted successfully!');
    }

    /**
     * Handle typing indicator events.
     */
    public function typing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|exists:users,id',
            'is_typing' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        // Broadcast typing event
        try {
            broadcast(new UserTyping(
                Auth::id(),
                Auth::user()->name,
                $request->recipient_id,
                $request->is_typing
            ))->toOthers();
        } catch (\Exception $e) {
            // Log the error but don't fail the operation
            Log::error('Failed to broadcast typing event: ' . $e->getMessage());
        }

        return response()->json(['success' => true]);
    }
}
