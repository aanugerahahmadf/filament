<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $otp = '';
    public string $email = '';
    public string $phone_number = '';
    public bool $otpSent = false;
    public string $message = '';
    public string $messageType = 'info';
    public bool $isPostRegistration = false;

    public function mount()
    {
        // Check if this is post-registration verification
        $userId = Session::get('registered_user_id');

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $this->email = $user->email;
                $this->phone_number = $user->phone_number ?? '';
                $this->isPostRegistration = true;

                // Auto-send WhatsApp OTP
                $this->sendWhatsAppOtp();
                return;
            }
        }

        // Get the authenticated user's email (for login flow)
        if (Auth::check()) {
            $this->email = Auth::user()->email;
            $this->phone_number = Auth::user()->phone_number ?? '';

            // Auto-send WhatsApp OTP for login flow
            if (!empty($this->phone_number)) {
                $this->sendWhatsAppOtp();
            }
        }
    }

    /**
     * Send OTP via WhatsApp
     */
    public function sendWhatsAppOtp()
    {
        if ($this->isPostRegistration) {
            $userId = Session::get('registered_user_id');
            if (!$userId) {
                $this->message = 'Registration session expired. Please register again.';
                $this->messageType = 'error';
                return;
            }

            $user = User::find($userId);
            if (!$user) {
                $this->message = 'User not found.';
                $this->messageType = 'error';
                return;
            }
        } else {
            // For login flow, validate email
            $this->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            // Find user
            $user = User::where('email', $this->email)->first();

            if (!$user) {
                $this->message = 'User not found.';
                $this->messageType = 'error';
                return;
            }
        }

        // Check if user has a phone number
        if (empty($user->phone_number)) {
            $this->message = 'No phone number found for this account. Please update your profile.';
            $this->messageType = 'error';
            return;
        }

        // Send WhatsApp OTP
        if ($user->sendWhatsAppOtp()) {
            $this->otpSent = true;
            $this->message = "OTP sent via WhatsApp to {$user->phone_number}.";
            $this->messageType = 'success';
        } else {
            $this->message = 'Failed to send OTP via WhatsApp. Please try again.';
            $this->messageType = 'error';
        }
    }

    /**
     * Verify the OTP entered by the user
     */
    public function verifyOtp()
    {
        $this->validate([
            'otp' => 'required|digits:6',
        ]);

        // For post-registration flow
        if ($this->isPostRegistration) {
            $userId = Session::get('registered_user_id');
            if (!$userId) {
                $this->message = 'Registration session expired. Please register again.';
                $this->messageType = 'error';
                return;
            }

            $user = User::find($userId);
            if (!$user) {
                $this->message = 'User not found.';
                $this->messageType = 'error';
                return;
            }

            $this->email = $user->email;
        } else {
            // For login flow
            $user = User::where('email', $this->email)->first();
            if (!$user) {
                $this->message = 'User not found.';
                $this->messageType = 'error';
                return;
            }
        }

        // Check if OTP exists and is not expired
        if (!$user->otp_code || !$user->otp_expires_at || now()->isAfter($user->otp_expires_at)) {
            $this->message = 'OTP has expired. Please request a new one.';
            $this->messageType = 'error';
            return;
        }

        // Verify OTP
        if (!password_verify($this->otp, $user->otp_code)) {
            $this->message = 'Invalid OTP. Please try again.';
            $this->messageType = 'error';
            return;
        }

        // Clear OTP data
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // For post-registration flow, verify email and log in
        if ($this->isPostRegistration) {
            // Mark email as verified
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            // Log the user in
            Auth::login($user);

            // Clear registration session data
            Session::forget('registered_user_id');

            // Regenerate session
            Session::regenerate();

            $this->message = 'Account verified successfully. You are now logged in.';
            $this->messageType = 'success';

            // Redirect to dashboard
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        } else {
            // For login flow with OTP enabled
            if ($user->google2fa_enabled) {
                $user->update(['google2fa_enabled' => true]);
            }

            // Log the user in
            Auth::login($user);

            // Regenerate session
            Session::regenerate();

            $this->message = 'OTP verified successfully. You are now logged in.';
            $this->messageType = 'success';

            // Redirect to dashboard
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        }
    }

    /**
     * Resend OTP via WhatsApp
     */
    public function resendOtp()
    {
        $this->otpSent = false;
        $this->otp = '';
        $this->sendWhatsAppOtp();
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex justify-center">
        <x-app-logo />
    </div>

    <x-auth-header
        :title="__('WhatsApp OTP Verification')"
        :description="__('PT. Kilang Pertamina Internasional - Refinery Unit VI Balongan')"
    />

    <!-- Message Display -->
    @if ($message)
        <div class="p-4 rounded-lg text-center
            @if ($messageType === 'success') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
            @elseif ($messageType === 'error') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
            @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
            {{ $message }}
        </div>
    @endif

    <form method="POST" wire:submit.prevent="verifyOtp" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            :disabled="$otpSent || $isPostRegistration"
            autocomplete="email"
        />

        <!-- Phone Number -->
        @if ($phone_number)
            <flux:input
                wire:model="phone_number"
                :label="__('Phone Number')"
                type="text"
                disabled
            />
        @endif

        <!-- OTP Input -->
        @if ($otpSent)
            <div class="relative">
                <flux:input
                    wire:model="otp"
                    :label="__('OTP Code')"
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    required
                    autocomplete="one-time-code"
                />

                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ __('Enter the 6-digit code sent to your WhatsApp number') }}
                </div>
            </div>
        @endif

        <div class="flex flex-col gap-3">
            @if (!$otpSent)
                <flux:button
                    wire:click="sendWhatsAppOtp"
                    variant="primary"
                    class="w-full"
                    type="button"
                >
                    {{ __('Send OTP via WhatsApp') }}
                </flux:button>
            @else
                <flux:button
                    variant="primary"
                    type="submit"
                    class="w-full"
                >
                    {{ __('Verify OTP') }}
                </flux:button>

                <flux:button
                    wire:click="resendOtp"
                    variant="ghost"
                    class="w-full"
                    type="button"
                >
                    {{ __('Resend OTP via WhatsApp') }}
                </flux:button>
            @endif
        </div>
    </form>
</div>
