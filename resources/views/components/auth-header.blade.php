@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center mb-6">
    <flux:heading size="xl">{{ $title }}</flux:heading>
    <flux:subheading class="mt-2">{{ $description }}</flux:subheading>
</div>
