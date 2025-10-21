<?php

use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Title('Settings')] class extends Component {
    public $appearance;

    public function mount()
    {
        // Initialize with the current appearance setting or default to 'system'
        $this->appearance = session('flux-appearance', 'system');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <div x-data="{
            appearance: @js(session('flux-appearance', 'system')),
            updateAppearance(value) {
                this.appearance = value;
                localStorage.setItem('flux-appearance', value);
                window.dispatchEvent(new CustomEvent('flux-appearance', { detail: value }));

                // Apply theme immediately
                document.documentElement.classList.remove('dark');
                if (value === 'dark') {
                    document.documentElement.classList.add('dark');
                } else if (value === 'system') {
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    }
                }
            }
        }">
            <flux:radio.group variant="segmented" x-model="appearance" x-on:change="updateAppearance(appearance)">
                <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
            </flux:radio.group>
        </div>
    </x-settings.layout>
</section>
