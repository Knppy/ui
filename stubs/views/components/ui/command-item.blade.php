@props([
    'value' => null,
    'disabled' => false,
])

<div
    role="option"
    tabindex="-1"
    data-slot="command-item"
    data-value="{{ $value ?? '' }}"
    data-selected="false"
    @if ($disabled) data-disabled="true" aria-disabled="true" @endif
    x-init="$el.dataset.value ||= $el.textContent.trim()"
    x-on:pointermove="activate($el)"
    x-on:click="select($el)"
    {{ $attributes->twMerge(['class' => 'relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none hover:bg-accent hover:text-accent-foreground data-[disabled=true]:pointer-events-none data-[disabled=true]:opacity-50 data-[selected=true]:bg-accent data-[selected=true]:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground']) }}
>
    {{ $slot }}
</div>
