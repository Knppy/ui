@props([
    'value' => null,
    'viewport' => true,
])

<nav
    x-data="uiNavigationMenu(@js($value), @js($viewport))"
    x-init="initialize($el)"
    x-modelable="value"
    x-on:keydown.escape.window="closeMenu()"
    x-on:click.outside="closeMenu()"
    data-slot="navigation-menu"
    data-viewport="{{ $viewport ? 'true' : 'false' }}"
    {{ $attributes->whereStartsWith(['x-model', 'wire:model']) }}
    {{ $attributes->whereDoesntStartWith(['x-model', 'wire:model'])->twMerge(['class' => 'group/navigation-menu relative flex max-w-max flex-1 items-center justify-center']) }}
>
    {{ $slot }}

    @if ($viewport)
        <x-ui.navigation-menu-viewport />
    @endif
</nav>
