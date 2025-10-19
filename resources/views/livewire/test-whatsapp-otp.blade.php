<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public function redirectToWhatsAppOtp()
    {
        return $this->redirect(route('whatsapp.otp.verify'), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="flex justify-center">
        <x-app-logo />
    </div>

    <x-auth-header
        :title="__('Test WhatsApp OTP')"
        :description="__('PT. Kilang Pertamina Internasional - Refinery Unit VI Balongan')"
    />

    <div class="flex flex-col gap-4">
        <p>This is a test page to verify the WhatsApp OTP verification component.</p>

        <flux:button wire:click="redirectToWhatsAppOtp" variant="primary">
            {{ __('Test WhatsApp OTP Verification') }}
        </flux:button>

        <flux:link :href="route('login')" wire:navigate>
            {{ __('Back to Login') }}
        </flux:link>
    </div>
</div>
