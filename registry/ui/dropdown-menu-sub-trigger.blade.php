@props([
    'inset' => false,
])

<div
    data-slot="dropdown-menu-sub-trigger"
    @if ($inset) data-inset="true" @endif
    :data-state="subOpen ? 'open' : 'closed'"
    role="menuitem"
    aria-haspopup="menu"
    :aria-expanded="subOpen"
    tabindex="0"
    x-ref="subTrigger"
    {{ $attributes->twMerge('flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground data-[inset]:pl-8 data-[state=open]:bg-accent data-[state=open]:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground') }}
>
    {{ $slot }}
    <x-lucide-chevron-right class="ml-auto" />
</div>
