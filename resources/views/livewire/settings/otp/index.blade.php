<?php

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Title('Settings')] class extends Component {
    public bool $otpEnabled = false;
    public string $message = '';
    public string $messageType = 'info';

    public function mount()
    {
        $this->otpEnabled = auth()->user()->google2fa_enabled;
    }

    /**
     * Enable OTP for the user
     */
    public function enableOtp()
    {
        $user = auth()->user();

        // Generate OTP and send it via email
        if ($user->sendOtp()) {
            // Update user to enable OTP
            $user->update(['google2fa_enabled' => true]);

            $this->otpEnabled = true;
            $this->message = 'OTP has been enabled and a test code has been sent to your email.';
            $this->messageType = 'success';
        } else {
            $this->message = 'Failed to send OTP. Please try again.';
            $this->messageType = 'error';
        }
    }

    /**
     * Disable OTP for the user
     */
    public function disableOtp()
    {
        $user = auth()->user();

        // Disable OTP
        $user->update([
            'google2fa_enabled' => false,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        $this->otpEnabled = false;
        $this->message = 'OTP has been disabled.';
        $this->messageType = 'success';
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout
        :heading="__('Email OTP Authentication')"
        :subheading="__('Manage your email-based OTP authentication settings')"
    >
        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            <!-- Message Display -->
            @if ($message)
                <div class="p-4 rounded-lg text-center
                    @if ($messageType === 'success') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @elseif ($messageType === 'error') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif">
                    {{ $message }}
                </div>
            @endif

            @if ($otpEnabled)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="green">{{ __('Enabled') }}</flux:badge>
                    </div>

                    <flux:text>
                        {{ __('Email-based OTP authentication is currently enabled. You will receive a 6-digit code via email during login.') }}
                    </flux:text>

                    <div class="flex justify-start">
                        <flux:button
                            variant="danger"
                            icon="shield-exclamation"
                            icon:variant="outline"
                            wire:click="disableOtp"
                        >
                            {{ __('Disable Email OTP') }}
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="red">{{ __('Disabled') }}</flux:badge>
                    </div>

                    <flux:text variant="subtle">
                        {{ __('When you enable email-based OTP authentication, you will receive a 6-digit code via email during login for enhanced security.') }}
                    </flux:text>

                    <flux:button
                        variant="primary"
                        icon="shield-check"
                        icon:variant="outline"
                        wire:click="enableOtp"
                    >
                        {{ __('Enable Email OTP') }}
                    </flux:button>
                </div>
            @endif
        </div>
    </x-settings.layout>
</section>
