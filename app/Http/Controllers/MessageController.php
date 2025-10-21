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
            'body' => 'required|string|max:1000', // Changed from 'message' to 'body'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $message = Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'body' => $request->body, // Use 'body' instead of 'message'
            'subject' => 'Message',
            'type' => 'message',
            'priority' => 'normal',
            'delivered_at' => now(),
        ]);

        // Create notification for the recipient
        $recipient = User::find($request->to_user_id);
        $sender = Auth::user();
        if ($recipient && $sender) {
            $this->notificationService->sendMessageNotification($recipient, $sender, $request->body);
        }

        // Broadcast events
        broadcast(new MessageSent($message))->toOthers();
        broadcast(new MessageDelivered($message))->toOthers();

        if ($request->ajax()) {
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
            broadcast(new MessageReadReceipt($lastMessage))->toOthers();
        }

        return view('messages.messages-box', compact('user', 'messages', 'userId'));
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
        broadcast(new UserTyping(
            Auth::id(),
            Auth::user()->name,
            $request->recipient_id,
            $request->is_typing
        ))->toOthers();

        return response()->json(['success' => true]);
    }
}
