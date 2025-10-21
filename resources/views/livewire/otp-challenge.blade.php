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
    public string $message = '';
    public string $messageType = 'info';
    public string $verificationMethod = 'email'; // email, sms, whatsapp

    public function mount()
    {
        // Check if we have a user ID in the session
        $userId = Session::get('login.id');

        if (!$userId) {
            // No user ID in session, redirect to login
            $this->redirect(route('login'), navigate: true);
            return;
        }

        // Get the user
        $user = User::find($userId);

        if (!$user) {
            // User not found, redirect to login
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $this->email = $user->email;
        $this->phone_number = $user->phone_number ?? '';

        // Determine default verification method
        if (!empty($this->phone_number)) {
            $this->verificationMethod = 'sms';
        }
    }

    /**
     * Send OTP via the selected method
     */
    public function sendOtp()
    {
        // Get user ID from session
        $userId = Session::get('login.id');

        if (!$userId) {
            $this->message = 'Session expired. Please log in again.';
            $this->messageType = 'error';
            return;
        }

        // Find user
        $user = User::find($userId);

        if (!$user) {
            $this->message = 'User not found. Please log in again.';
            $this->messageType = 'error';
            return;
        }

        $success = false;

        switch ($this->verificationMethod) {
            case 'email':
                $success = $user->sendOtp();
                break;
            case 'sms':
                if (empty($user->phone_number)) {
                    $this->message = 'No phone number found for this account. Please update your profile or use email verification.';
                    $this->messageType = 'error';
                    return;
                }
                $success = $user->sendSmsOtp();
                break;
            case 'whatsapp':
                if (empty($user->phone_number)) {
                    $this->message = 'No phone number found for this account. Please update your profile or use email verification.';
                    $this->messageType = 'error';
                    return;
                }
                $success = $user->sendWhatsAppOtp();
                break;
        }

        if ($success) {
            $method = ucfirst($this->verificationMethod);
            $this->message = "OTP sent via {$method}.";
            $this->messageType = 'success';
        } else {
            $this->message = 'Failed to send OTP. Please try again.';
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

        // Get user ID from session
        $userId = Session::get('login.id');

        if (!$userId) {
            $this->message = 'Session expired. Please log in again.';
            $this->messageType = 'error';
            return;
        }

        // Find user
        $user = User::find($userId);

        if (!$user) {
            $this->message = 'User not found. Please log in again.';
            $this->messageType = 'error';
            return;
        }

        // Check if OTP exists and is not expired
        if (!$user->otp_code || !$user->otp_expires_at || now()->isAfter($user->otp_expires_at)) {
            $this->message = 'OTP has expired. Please log in again to request a new one.';
            $this->messageType = 'error';
            return;
        }

        // Verify OTP
        if (!password_verify($this->otp, $user->otp_code)) {
            $this->message = 'Invalid OTP. Please try again.';
            $this->messageType = 'error';
            return;
        }

        // OTP is valid, clear OTP data
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Get remember preference from session
        $remember = Session::get('login.remember', false);

        // Log the user in
        Auth::login($user, $remember);

        // Clear login session data
        Session::forget(['login.id', 'login.remember']);

        // Regenerate session
        Session::regenerate();

        $this->message = 'OTP verified successfully. You are now logged in.';
        $this->messageType = 'success';

        // Redirect to dashboard
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Resend OTP
     */
    public function resendOtp()
    {
        $this->otp = '';
        $this->sendOtp();
    }

    /**
     * Change verification method
     */
    public function changeMethod($method)
    {
        $this->verificationMethod = $method;
        $this->otp = '';
        $this->message = '';
        $this->sendOtp();
    }

    /**
     * Cancel login and return to login page
     */
    public function cancel()
    {
        // Clear login session data
        Session::forget(['login.id', 'login.remember']);

        // Redirect to login
        $this->redirect(route('login'), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('Two-Factor Authentication')"
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

    <form method="POST" wire:submit="verifyOtp" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model.live="email"
            :label="__('Email address')"
            type="email"
            disabled
        />

        <!-- Phone Number (if available) -->
        @if ($phone_number)
            <flux:input
                wire:model.live="phone_number"
                :label="__('Phone Number')"
                type="text"
                disabled
            />
        @endif

        <!-- Verification Method Selection -->
        <div class="flex flex-col gap-2">
            <flux:label>{{ __('Select Verification Method') }}</flux:label>
            <div class="flex gap-2">
                <flux:button
                    wire:click="changeMethod('email')"
                    :variant="$verificationMethod === 'email' ? 'primary' : 'ghost'"
                    type="button"
                    class="flex-1"
                >
                    {{ __('Email') }}
                </flux:button>

                @if ($phone_number)
                    <flux:button
                        wire:click="changeMethod('sms')"
                        :variant="$verificationMethod === 'sms' ? 'primary' : 'ghost'"
                        type="button"
                        class="flex-1"
                    >
                        {{ __('SMS') }}
                    </flux:button>

                    <flux:button
                        wire:click="changeMethod('whatsapp')"
                        :variant="$verificationMethod === 'whatsapp' ? 'primary' : 'ghost'"
                        type="button"
                        class="flex-1"
                    >
                        {{ __('WhatsApp') }}
                    </flux:button>
                @endif
            </div>
        </div>

        <!-- OTP Input -->
        <div class="relative">
            <flux:input
                wire:model.live="otp"
                :label="__('OTP Code')"
                type="text"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="6"
                required
                autocomplete="one-time-code"
            />

            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ __('Enter the 6-digit code sent to your ' . $verificationMethod) }}
            </div>
        </div>

        <div class="flex flex-col gap-3">
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
                {{ __('Resend OTP via ' . ucfirst($verificationMethod)) }}
            </flux:button>

            <flux:button
                wire:click="cancel"
                variant="ghost"
                class="w-full"
                type="button"
            >
                {{ __('Cancel') }}
            </flux:button>
        </div>
    </form>
</div>
