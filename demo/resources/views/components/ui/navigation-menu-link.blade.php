@props(['active' => false])

<a
    data-slot="navigation-menu-link"
    data-active="{{ $active ? 'true' : 'false' }}"
    @if ($active) aria-current="page" @endif
    {{ $attributes->twMerge(['class' => 'flex flex-col gap-1 rounded-sm p-2 text-sm transition-all outline-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 data-[active=true]:bg-accent/50 data-[active=true]:text-accent-foreground']) }}
>
    {{ $slot }}
</a>
