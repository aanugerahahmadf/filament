<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Title('Settings')] class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $place_of_birth = '';
    public string $city = '';
    public string $date_of_birth = '';
    public string $phone_number = '';
    public $avatar;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->username = Auth::user()->username;
        $this->email = Auth::user()->email;
        $this->place_of_birth = Auth::user()->place_of_birth ?? '';
        $this->city = Auth::user()->city ?? '';
        $this->date_of_birth = Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('Y-m-d') : '';
        $this->phone_number = Auth::user()->phone_number ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send email verification notification.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Send SMS OTP for phone verification
     */
    public function sendSmsOtp(): void
    {
        $user = Auth::user();

        // Update phone number if it has changed
        if ($this->phone_number !== $user->phone_number) {
            $user->update(['phone_number' => $this->phone_number]);
        }

        // Send SMS OTP
        if ($user->sendSmsOtp()) {
            $this->dispatch('notification', [
                'title' => 'OTP Sent',
                'body' => 'OTP has been sent to your phone number via SMS.',
                'type' => 'success'
            ]);
        } else {
            $this->dispatch('notification', [
                'title' => 'Error',
                'body' => 'Failed to send OTP via SMS. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    /**
     * Send WhatsApp OTP for phone verification
     */
    public function sendWhatsAppOtp(): void
    {
        $user = Auth::user();

        // Update phone number if it has changed
        if ($this->phone_number !== $user->phone_number) {
            $user->update(['phone_number' => $this->phone_number]);
        }

        // Send WhatsApp OTP
        if ($user->sendWhatsAppOtp()) {
            $this->dispatch('notification', [
                'title' => 'OTP Sent',
                'body' => 'OTP has been sent to your phone number via WhatsApp.',
                'type' => 'success'
            ]);
        } else {
            $this->dispatch('notification', [
                'title' => 'Error',
                'body' => 'Failed to send OTP via WhatsApp. Please try again.',
                'type' => 'error'
            ]);
        }
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <!-- Notification display -->
        @if (session('status'))
            <flux:alert :variant="session('status') === 'verification-link-sent' ? 'success' : 'info'" :message="session('status') === 'verification-link-sent' ? __('A new verification link has been sent to your email address.') : session('status')" />
        @endif

        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <!-- Add notification handler -->
            <script>
                document.addEventListener('livewire:init', () => {
                    Livewire.on('notification', (data) => {
                        const notification = data[0];
                        const alert = document.createElement('flux-alert');
                        alert.variant = notification.type === 'error' ? 'danger' : notification.type;
                        alert.message = notification.body;
                        alert.setAttribute('class', 'mb-4');
                        document.querySelector('form').insertAdjacentElement('beforebegin', alert);

                        // Remove alert after 5 seconds
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 5000);
                    });
                });
            </script>

            <!-- Avatar -->
            <div>
                <flux:label>{{ __('Profile Photo') }}</flux:label>
                <div class="flex items-center gap-6 mt-2">
                    <!-- Current avatar preview -->
                    <div class="relative">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-white font-bold">
                                {{ Auth::user()->initials() }}
                            </div>
                        @endif
                    </div>

                    <!-- File upload -->
                    <div class="flex-1">
                        <flux:input.file
                            wire:model="avatar"
                            accept="image/*"
                        />
                        <flux:text class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('JPG, PNG, or GIF. Max 50MB.') }}
                        </flux:text>
                        @error('avatar')
                            <flux:text class="mt-1 text-sm text-red-500">{{ $message }}</flux:text>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Name -->
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <!-- Username -->
            <flux:input wire:model="username" :label="__('Username')" type="text" required autocomplete="username" />

            <!-- Place of Birth -->
            <flux:input wire:model="place_of_birth" :label="__('Tempat Lahir')" type="text" autocomplete="place_of_birth" />

            <!-- City -->
            <flux:input wire:model="city" :label="__('Kota')" type="text" autocomplete="city" />

            <!-- Date of Birth -->
            <div>
                <flux:label for="date_of_birth">{{ __('Tanggal Lahir') }}</flux:label>
                <input
                    wire:model="date_of_birth"
                    id="date_of_birth"
                    type="date"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-zinc-100 dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 focus:ring-offset-white dark:focus:ring-offset-zinc-900 focus:outline-none py-3 px-4 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 transition duration-200"
                />
            </div>

            <!-- Phone Number -->
            <div class="space-y-2">
                <flux:input wire:model="phone_number" :label="__('No. Handphone / WhatsApp')" type="text" autocomplete="phone_number" />

                <!-- Phone Verification Status and Actions -->
                @if($phone_number)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center gap-2">
                            @if($this->user->phone_verified)
                                <flux:icon name="check-circle" class="w-5 h-5 text-green-500" />
                                <span class="text-sm text-green-600 dark:text-green-400">{{ __('Phone Verified') }}</span>
                                @if($this->user->phone_verification_method)
                                    <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
                                        {{ ucfirst($this->user->phone_verification_method) }}
                                    </span>
                                @endif
                            @else
                                <flux:icon name="x-circle" class="w-5 h-5 text-red-500" />
                                <span class="text-sm text-red-600 dark:text-red-400">{{ __('Phone Not Verified') }}</span>
                            @endif
                        </div>

                        @if($phone_number && !$this->user->phone_verified)
                            <div class="flex gap-2">
                                <flux:button wire:click="sendSmsOtp" size="sm" variant="primary">
                                    {{ __('Verify via SMS') }}
                                </flux:button>
                                <flux:button wire:click="sendWhatsAppOtp" size="sm" variant="primary">
                                    {{ __('Verify via WhatsApp') }}
                                </flux:button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Email -->
            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
