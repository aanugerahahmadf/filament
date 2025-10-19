<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';
    public string $otp = '';
    public bool $otpSent = false;
    public string $message = '';
    public string $messageType = 'info';
    public string $token = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // Check if user exists
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->message = 'No account found with that email address.';
            $this->messageType = 'error';
            return;
        }

        // Generate and send OTP
        if ($user->sendOtp()) {
            // Store user ID in session for OTP verification
            Session::put('password_reset_user_id', $user->id);
            $this->otpSent = true;
            $this->message = 'OTP sent to your email address. Please enter the code to proceed with password reset.';
            $this->messageType = 'success';
        } else {
            $this->message = 'Failed to send OTP. Please try again.';
            $this->messageType = 'error';
        }
    }

    /**
     * Verify the OTP entered by the user
     */
    public function verifyOtp(): void
    {
        $this->validate([
            'otp' => 'required|digits:6',
        ]);

        // Get user from session
        $userId = Session::get('password_reset_user_id');
        if (!$userId) {
            $this->message = 'Session expired. Please request a new OTP.';
            $this->messageType = 'error';
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->message = 'User not found.';
            $this->messageType = 'error';
            return;
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

        // Generate a password reset token directly
        $this->token = Password::createToken($user);

        // Store email in session for the reset form
        Session::put('password_reset_email', $user->email);

        // Clear the password reset user ID from session
        Session::forget('password_reset_user_id');

        // Redirect directly to the password reset page
        $this->redirect(route('password.reset', [
            'token' => $this->token,
            'email' => $user->email
        ]), navigate: true);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(): void
    {
        $this->otp = '';
        $this->sendPasswordResetLink();
    }

    /**
     * Reset form to initial state
     */
    public function resetForm(): void
    {
        $this->otp = '';
        $this->otpSent = false;
        $this->message = '';
        $this->token = '';
        Session::forget('password_reset_user_id');
        Session::forget('password_reset_email');
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex justify-center">
        <x-app-logo />
    </div>

    <x-auth-header
        :title="__($otpSent ? 'Verify Email Code OTP' : 'Forgot password')"
        :description="__($otpSent ? 'Enter the OTP code sent to your email address to proceed with password reset' : 'Enter your email to receive a password reset link')"
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

    <form method="POST" wire:submit="{{ $otpSent ? 'verifyOtp' : 'sendPasswordResetLink' }}" class="flex flex-col gap-6">
        <!-- Email Address -->
        @if (!$otpSent)
            <flux:input
                wire:model="email"
                :label="__('Email Address')"
                type="email"
                required
                autofocus
            />
        @else
            <div>
                <flux:input
                    wire:model="email"
                    :label="__('Email Address')"
                    type="email"
                    disabled
                />
            </div>

            <!-- OTP Input -->
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
                    {{ __('Enter the 6-digit code sent to your email') }}
                </div>
            </div>
        @endif

        <div class="flex flex-col gap-3">
            @if (!$otpSent)
                <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                    {{ __('Send OTP') }}
                </flux:button>
            @else
                <flux:button variant="primary" type="submit" class="w-full">
                    {{ __('Verify OTP & Reset Password') }}
                </flux:button>

                <flux:button wire:click="resendOtp" variant="primary" type="button" class="w-full">
                    {{ __('Resend OTP') }}
                </flux:button>

                <flux:button wire:click="resetForm" variant="primary" type="button" class="w-full">
                    {{ __('Use Different Email') }}
                </flux:button>
            @endif
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('Or, return to') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('log in') }}</flux:link>
    </div>
</div>
