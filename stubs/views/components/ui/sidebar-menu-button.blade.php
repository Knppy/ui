@props([
    'is' => 'button',
    'active' => false,
    'variant' => 'default',
    'size' => 'default',
    'tooltip' => null,
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left text-sm outline-none transition-[width,height,padding] hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 focus-visible:ring-sidebar-ring disabled:pointer-events-none disabled:opacity-50 data-[active=true]:bg-sidebar-accent data-[active=true]:font-medium data-[active=true]:text-sidebar-accent-foreground group-data-[collapsible=icon]:size-8 group-data-[collapsible=icon]:p-2 [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0')
        ->add($variant === 'outline' ? 'bg-background shadow-[0_0_0_1px_var(--sidebar-border)]' : '')
        ->add(match ($size) {
            'sm' => 'h-7 text-xs',
            'lg' => 'h-12 text-sm group-data-[collapsible=icon]:p-0',
            default => 'h-8 text-sm',
        });
@endphp

@if ($tooltip)
    <x-ui.tooltip :delay-duration="0">
        <x-ui.tooltip-trigger>
            <{{ $is }}
                @if ($is === 'button') type="button" @endif
                data-slot="sidebar-menu-button"
                data-sidebar="menu-button"
                data-size="{{ $size }}"
                data-active="{{ $active ? 'true' : 'false' }}"
                {{ $attributes->twMerge(['class' => $classes]) }}
            >
                {{ $slot }}
            </{{ $is }}>
        </x-ui.tooltip-trigger>
        <x-ui.tooltip-content side="right" align="center" x-show="state === 'collapsed' && ! isMobile">
            {{ $tooltip }}
        </x-ui.tooltip-content>
    </x-ui.tooltip>
@else
    <{{ $is }}
        @if ($is === 'button') type="button" @endif
        data-slot="sidebar-menu-button"
        data-sidebar="menu-button"
        data-size="{{ $size }}"
        data-active="{{ $active ? 'true' : 'false' }}"
        {{ $attributes->twMerge(['class' => $classes]) }}
    >
        {{ $slot }}
    </{{ $is }}>
@endif
