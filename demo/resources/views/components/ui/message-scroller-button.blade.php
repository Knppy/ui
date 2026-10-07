@props([
    'direction' => 'end',
    'variant' => 'secondary',
    'size' => 'icon-sm',
])

<x-ui.button
    dataSlot="message-scroller-button"
    :variant="$variant"
    :size="$size"
    data-direction="{{ $direction }}"
    x-cloak
    x-show="isButtonActive('{{ $direction }}')"
    x-bind:data-active="isButtonActive('{{ $direction }}')"
    x-bind:aria-hidden="! isButtonActive('{{ $direction }}')"
    x-bind:tabindex="isButtonActive('{{ $direction }}') ? 0 : -1"
    x-on:click="scrollToEdge('{{ $direction }}')"
    {{ $attributes->twMerge(['class' => 'border-border bg-background text-foreground absolute start-1/2 -translate-x-1/2 transition-[translate,scale,opacity] duration-200 hover:bg-muted hover:text-foreground data-[active=false]:pointer-events-none data-[active=false]:scale-95 data-[active=false]:opacity-0 data-[active=false]:duration-400 data-[active=true]:translate-y-0 data-[active=true]:scale-100 data-[active=true]:opacity-100 data-[direction=end]:bottom-4 data-[direction=end]:data-[active=false]:translate-y-full data-[direction=start]:top-4 data-[direction=start]:data-[active=false]:-translate-y-full data-[direction=start]:[&_svg]:rotate-180 rtl:translate-x-1/2']) }}
>
    @if ($slot->isEmpty())
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 5v14" />
            <path d="m19 12-7 7-7-7" />
        </svg>
        <span class="sr-only">{{ $direction === 'end' ? __('Scroll to end') : __('Scroll to start') }}</span>
    @else
        {{ $slot }}
    @endif
</x-ui.button>
