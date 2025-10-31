<?php

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\MessageResource;
use App\Models\Message;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\PageRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Filament\Panel;

class ChatInterface extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = MessageResource::class;
    protected static ?string $title = 'Chat Interface';
    protected static ?string $navigationLabel = 'Chat Interface';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.resources.messages.pages.chat-interface';

    public ?array $data = [];
    public ?User $selectedUser = null;
    public ?int $selectedUserId = null;
    public array $messages = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function route(string $path): PageRegistration
    {
        return new PageRegistration(
            page: static::class,
            route: fn (Panel $panel): Route => RouteFacade::get($path, static::class)
                ->middleware(static::getRouteMiddleware($panel))
                ->withoutMiddleware(static::getWithoutRouteMiddleware($panel)),
        );
    }

    public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
    {
        return $form
            ->components([
                Select::make('selectedUserId')
                    ->label('Select User to Chat With')
                    ->options(User::where('id', '!=', Auth::id())->pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedUserId = $state ? (int) $state : null;
                        $this->selectedUser = $this->selectedUserId ? User::find($this->selectedUserId) : null;
                        $this->loadMessages();
                    }),
                Textarea::make('message')
                    ->label('Message')
                    ->rows(3)
                    ->required()
                    ->placeholder('Type your message here...')
                    ->helperText('Press Enter to send'),
            ])
            ->statePath('data')
            ->columns(1);
    }

    public function loadMessages(): void
    {
        if (!$this->selectedUserId) {
            $this->messages = [];
            return;
        }

        $this->messages = Message::with(['fromUser', 'toUser'])
            ->where(function ($query) {
                $query->where('from_user_id', Auth::id())
                    ->where('to_user_id', $this->selectedUserId);
            })
            ->orWhere(function ($query) {
                $query->where('from_user_id', $this->selectedUserId)
                    ->where('to_user_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();

        // Mark messages as read
        Message::where('from_user_id', $this->selectedUserId)
            ->where('to_user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Dispatch event for real-time updates
        $this->dispatch('messages-loaded');
    }

    public function selectUser(int $userId): void
    {
        $this->selectedUserId = $userId;
        $this->selectedUser = User::find($userId);
        $this->loadMessages();
        $this->dispatch('user-selected', userId: $userId);
    }

    public function sendMessage(): void
    {
        if (!$this->selectedUserId) {
            Notification::make()
                ->title('Please select a user to chat with')
                ->danger()
                ->send();
            return;
        }

        $data = $this->form->getState();

        if (empty($data['message'])) {
            Notification::make()
                ->title('Please enter a message')
                ->danger()
                ->send();
            return;
        }

        $message = Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $this->selectedUserId,
            'body' => $data['message'],
            'type' => 'message',
            'priority' => 'medium',
            'delivered_at' => now(),
        ]);

        // Reset message field
        $this->data['message'] = '';

        // Reload messages
        $this->loadMessages();

        // Send real-time notification
        Notification::make()
            ->title('Message sent successfully')
            ->success()
            ->send();

        $this->dispatch('message-sent', messageId: $message->id);
    }

    public function deleteMessage(int $messageId): void
    {
        $message = Message::find($messageId);

        if (!$message) {
            Notification::make()
                ->title('Message not found')
                ->danger()
                ->send();
            return;
        }

        // Check if user is authorized to delete this message
        if ($message->from_user_id !== Auth::id() && $message->to_user_id !== Auth::id()) {
            Notification::make()
                ->title('You are not authorized to delete this message')
                ->danger()
                ->send();
            return;
        }

        $message->delete();

        $this->loadMessages();

        Notification::make()
            ->title('Message deleted successfully')
            ->success()
            ->send();
    }

    public function getOnlineUsersProperty(): array
    {
        // In a real application, you would check user status
        // For now, we'll return all users except the current one
        return User::where('id', '!=', Auth::id())->get()->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-m-arrow-path')
                ->action(function () {
                    $this->loadMessages();
                }),
        ];
    }

    public function getSelectedUserProperty(): ?User
    {
        return $this->selectedUser;
    }
}
