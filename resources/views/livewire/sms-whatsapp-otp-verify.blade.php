<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $otp = '';
    public string $email = '';
    public string $phone_number = '';
    public bool $otpSent = false;
    public string $message = '';
    public string $messageType = 'info';
    public string $verificationMethod = 'email'; // email, sms, whatsapp
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

                // Use the verification method stored during registration
                $this->verificationMethod = $user->phone_verification_method ??
                                          (!empty($this->phone_number) ? 'sms' : 'email');

                // Auto-send OTP using the preferred method
                $this->sendOtp();
                return;
            }
        }

        // Get the authenticated user's email (for login flow)
        if (Auth::check()) {
            $this->email = Auth::user()->email;
            $this->phone_number = Auth::user()->phone_number ?? '';
        }
    }

    /**
     * Send OTP via the selected method
     */
    public function sendOtp()
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
            $this->otpSent = true;
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
     * Resend OTP
     */
    public function resendOtp()
    {
        $this->otpSent = false;
        $this->otp = '';
        $this->sendOtp();
    }

    /**
     * Change verification method
     */
    public function changeMethod($method)
    {
        $this->verificationMethod = $method;
        $this->otpSent = false;
        $this->otp = '';
        $this->message = '';
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex justify-center">
        <x-app-logo />
    </div>

    <x-auth-header
        :title="__('Account Verification')"
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
            required
            :disabled="$otpSent || $isPostRegistration"
            autocomplete="email"
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
        @if (!$otpSent && !$isPostRegistration)
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
        @else
            <div class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Verification method: ' . ucfirst($verificationMethod)) }}
                @if($isPostRegistration)
                    <div class="mt-1 p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                        <flux:text class="text-xs">{{ __('This verification method was selected during registration') }}</flux:text>
                    </div>
                @endif
            </div>
        @endif

        <!-- OTP Input -->
        @if ($otpSent)
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
        @endif

        <div class="flex flex-col gap-3">
            @if (!$otpSent && !$isPostRegistration)
                <flux:button
                    wire:click="sendOtp"
                    variant="primary"
                    class="w-full"
                    type="button"
                >
                    {{ __('Send OTP via ' . ucfirst($verificationMethod)) }}
                </flux:button>
            @elseif ($otpSent)
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
                    {{ __('Resend OTP') }}
                </flux:button>
            @else
                <!-- Post-registration flow -->
                <flux:button
                    wire:click="sendOtp"
                    variant="primary"
                    class="w-full"
                    type="button"
                >
                    {{ __('Send OTP via ' . ucfirst($verificationMethod)) }}
                </flux:button>
            @endif
        </div>
    </form>
</div>
