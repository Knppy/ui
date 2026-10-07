@props([
    'title' => 'Command Palette',
    'description' => 'Search for a command to run...',
    'open' => false,
    'showCloseButton' => true,
])

<x-ui.dialog :open="$open" {{ $attributes->whereStartsWith(['x-model', 'wire:model']) }}>
    <x-ui.dialog-header class="sr-only">
        <x-ui.dialog-title>{{ $title }}</x-ui.dialog-title>
        <x-ui.dialog-description>{{ $description }}</x-ui.dialog-description>
    </x-ui.dialog-header>
    <x-ui.dialog-content
        :show-close-button="$showCloseButton"
        {{ $attributes->whereDoesntStartWith(['x-model', 'wire:model'])->twMerge(['class' => 'overflow-hidden p-0']) }}
    >
        <x-ui.command class="[&_[data-slot=command-group]>div:first-child]:px-2 [&_[data-slot=command-group]>div:first-child]:font-medium [&_[data-slot=command-group]>div:first-child]:text-muted-foreground [&_[data-slot=command-group]]:px-2 [&_[data-slot=command-input-wrapper]_svg]:size-5 [&_[data-slot=command-input]]:h-12 [&_[data-slot=command-item]]:px-2 [&_[data-slot=command-item]]:py-3 [&_[data-slot=command-item]_svg]:size-5 **:data-[slot=command-input-wrapper]:h-12">
            {{ $slot }}
        </x-ui.command>
    </x-ui.dialog-content>
</x-ui.dialog>
