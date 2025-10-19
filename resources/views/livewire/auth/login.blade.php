<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';
    public string $password = '';

    public bool $remember = false;
    public bool $needsPasswordUpdate = false;
    public ?User $authenticatedUser = null;

    // Validation rules for the login form
    protected $rules = [
        'email' => ['required', 'string'],
        'password' => ['required', 'string'],
    ];

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        // Check if user has a strong password
        $hasStrongPassword = true;
        try {
            // Check if the column exists before accessing it
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'has_strong_password')) {
                $hasStrongPassword = $user->has_strong_password ?? true;
            }
        } catch (\Exception $e) {
            // If we can't check for the column, assume the password is strong
            $hasStrongPassword = true;
        }

        if (!$hasStrongPassword) {
            // Set flags to show password update prompt
            $this->needsPasswordUpdate = true;
            $this->authenticatedUser = $user;
            return;
        }

        // Check if user has 2FA enabled
        if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
            Session::put([
                'login.id' => $user->getKey(),
                'login.remember' => $this->remember,
            ]);

            // Send OTP for 2FA
            $otpSent = false;
            if (!empty($user->phone_number)) {
                // Try SMS first, fallback to WhatsApp, then email
                $otpSent = $user->sendSmsOtp() || $user->sendWhatsAppOtp();
            }

            if (!$otpSent) {
                // Fallback to email OTP
                $otpSent = $user->sendOtp();
            }

            if ($otpSent) {
                $this->redirect(route('sms.whatsapp.otp.verify'), navigate: true);
            } else {
                $this->addError('email', 'Failed to send OTP for two-factor authentication.');
            }

            return;
        }

        // Log the user in directly
        Auth::login($user, $this->remember);

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        // Redirect to the main dashboard, not the admin panel
        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Update the user's password to meet strength requirements.
     */
    public function updatePassword(): void
    {
        $this->validate([
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'],
        ]);

        if ($this->authenticatedUser) {
            // Prepare the data to update
            $updateData = [
                'password' => bcrypt($this->password),
            ];

            // Only add has_strong_password if the column exists
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'has_strong_password')) {
                    $updateData['has_strong_password'] = true;
                }
            } catch (\Exception $e) {
                // If we can't check for the column, we'll skip it
            }

            $this->authenticatedUser->update($updateData);

            // Authenticate the user with the new password
            Auth::login($this->authenticatedUser, $this->remember);

            $this->needsPasswordUpdate = false;
            // Redirect to the main dashboard, not the admin panel
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        }
    }

    /**
     * Skip password update (not recommended).
     */
    public function skipPasswordUpdate(): void
    {
        if ($this->authenticatedUser) {
            // Authenticate the user with the existing password
            Auth::login($this->authenticatedUser, $this->remember);

            $this->needsPasswordUpdate = false;
            // Redirect to the main dashboard, not the admin panel
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        }
    }

    /**
     * Validate the user's credentials.
     */
    protected function validateCredentials(): User
    {
        // Check if the input is an email or username
        $loginField = filter_var($this->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginField, $this->email)->first();

        if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <!-- Password Update Prompt -->
    @if ($needsPasswordUpdate)
        <div class="flex justify-center">
            <x-app-logo />
        </div>

        <x-auth-header
            :title="__('Password Update Required')"
            :description="__('PT. Kilang Pertamina Internasional - Refinery Unit VI Balongan')"
        />

        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
            <p>{{ __('For security reasons, we now require all passwords to be at least 8 characters long and include uppercase letters, lowercase letters, numbers, and special characters.') }}</p>
        </div>

        <form method="POST" wire:submit="updatePassword" class="flex flex-col gap-6">
            <!-- New Password -->
            <flux:input
                wire:model="password"
                :label="__('New Password (min. 8 characters with uppercase, lowercase, number, and special character)')"
                type="password"
                required
                autocomplete="new-password"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm New Password')"
                type="password"
                required
                autocomplete="new-password"
                viewable
            />

            <div class="flex flex-col gap-3">
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('Update Password') }}
                </flux:button>

                <flux:button wire:click="skipPasswordUpdate" variant="ghost" class="w-full">
                    {{ __('Skip for Now (Not Recommended)') }}
                </flux:button>
            </div>
        </form>
    @else
        <!-- Regular Login Form -->
        <div class="flex justify-center">
            <x-app-logo />
        </div>

        <x-auth-header
            :title="__('Log in to your account')"
            :description="__('PT. Kilang Pertamina Internasional - Refinery Unit VI Balongan')"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" wire:submit="login" class="flex flex-col gap-6">
            <!-- Email Address or Username -->
            <flux:input
                wire:model="email"
                :label="__('Email or Username')"
                type="text"
                required
                autocomplete="username"
            />

            <!-- Password -->
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                viewable
            />

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <flux:checkbox wire:model="remember" :label="__('Remember me')" />

                <flux:link :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
            </div>

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Register') }}</flux:link>
        </div>
    @endif
</div>
