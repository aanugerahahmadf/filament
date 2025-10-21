<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $place_of_birth = '';
    public string $city = '';
    public string $date_of_birth = '';
    public string $phone_number = '';
    public string $preferredVerificationMethod = 'email';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Add preferred verification method to validated data if phone number is provided
        if (!empty($validated['phone_number'])) {
            $validated['phone_verification_method'] = $this->preferredVerificationMethod;
        }

        $user = User::create($validated);

        event(new Registered($user));

        // Send OTP based on preferred verification method
        $otpSent = false;
        $verificationRoute = 'email.otp.verify';

        switch ($this->preferredVerificationMethod) {
            case 'whatsapp':
                if (!empty($user->phone_number)) {
                    $otpSent = $user->sendWhatsAppOtp();
                    $verificationRoute = 'whatsapp.otp.verify'; // Use dedicated WhatsApp OTP verification
                }
                break;
            case 'email':
            default:
                $otpSent = $user->sendOtp();
                $verificationRoute = 'email.otp.verify'; // Use dedicated email OTP verification
                break;
        }

        if ($otpSent) {
            // Store user ID in session for OTP verification
            Session::put('registered_user_id', $user->id);

            // Redirect to appropriate OTP verification page
            $this->redirect(route($verificationRoute), navigate: true);
        } else {
            // If OTP sending fails, log in normally
            Auth::login($user);
            Session::regenerate();
            $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
        }
    }

    /**
     * Set preferred verification method
     */
    public function setVerificationMethod($method): void
    {
        $this->preferredVerificationMethod = $method;
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex justify-center">
        <x-app-logo />
    </div>

    <x-auth-header
        :title="__('Create an account')"
        :description="__('PT. Kilang Pertamina Internasional - Refinery Unit VI Balongan')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model.live="name"
            :label="__('Full Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
        />

        <!-- Username -->
        <flux:input
            wire:model.live="username"
            :label="__('Username')"
            type="text"
            required
            autocomplete="username"
        />

        <!-- Email Address -->
        <flux:input
            wire:model.live="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <flux:input
            wire:model.live="password"
            :label="__('Password (min. 8 characters with uppercase, lowercase, number, and special character)')"
            type="password"
            required
            autocomplete="new-password"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model.live="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            viewable
        />

        <!-- Place of Birth -->
        <flux:input
            wire:model.live="place_of_birth"
            :label="__('Tempat Lahir')"
            type="text"
            autocomplete="place_of_birth"
        />

        <!-- City -->
        <flux:input
            wire:model.live="city"
            :label="__('Kota')"
            type="text"
            autocomplete="city"
        />

        <!-- Date of Birth -->
        <div>
            <flux:label for="date_of_birth">{{ __('Tanggal Lahir') }}</flux:label>
            <input
                wire:model.live="date_of_birth"
                id="date_of_birth"
                type="date"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-zinc-100 dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 focus:ring-offset-white dark:focus:ring-offset-zinc-900 focus:outline-none py-3 px-4 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 transition duration-200"
            />
        </div>

        <!-- Phone Number -->
        <flux:input
            wire:model.live="phone_number"
            :label="__('No. Handphone / WhatsApp')"
            type="text"
            autocomplete="phone_number"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
