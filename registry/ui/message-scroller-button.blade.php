@props([
    'direction' => 'end',
    'variant' => 'secondary',
    'size' => 'icon-sm',
])

@php
    $base = 'absolute inset-x-1/2 -translate-x-1/2 border border-border bg-background text-foreground transition-[translate,scale,opacity] duration-200 hover:bg-muted hover:text-foreground';

    $directionClasses = match ($direction) {
        'start' => 'top-4',
        default => 'bottom-4',
    };

    $buttonVariants = [
        'default' => 'bg-primary text-primary-foreground hover:bg-primary/90',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'outline' => 'border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground',
        'ghost' => 'hover:bg-accent hover:text-accent-foreground',
        'destructive' => 'bg-destructive text-white hover:bg-destructive/90',
        'link' => 'text-primary underline-offset-4 hover:underline',
    ];

    $buttonSizes = [
        'default' => 'h-9 px-4 py-2',
        'sm' => 'h-8 rounded-md px-3',
        'lg' => 'h-10 rounded-md px-6',
        'icon' => 'size-9',
        'icon-sm' => 'size-8',
        'icon-lg' => 'size-10',
    ];

    $scrollableFlag = $direction === 'start' ? 'canScrollStart' : 'canScrollEnd';
    $action = $direction === 'start' ? 'scrollToStart()' : 'scrollToEnd()';
@endphp

<button
    data-slot="message-scroller-button"
    data-direction="{{ $direction }}"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    type="button"
    :data-active="{{ $scrollableFlag }} ? 'true' : 'false'"
    :inert="! {{ $scrollableFlag }}"
    :tabindex="{{ $scrollableFlag }} ? 0 : -1"
    @click="{{ $action }}"
    {{
        $attributes->twMerge([
            $base,
            $directionClasses,
            'data-[active=false]:pointer-events-none data-[active=false]:scale-95 data-[active=false]:opacity-0 data-[active=false]:duration-[400ms] data-[active=false]:ease-[cubic-bezier(0.7,0,0.84,0)]',
            'data-[active=true]:translate-y-0 data-[active=true]:scale-100 data-[active=true]:opacity-100 data-[active=true]:ease-[cubic-bezier(0.23,1,0.32,1)]',
            $direction === 'end'
            ? 'data-[active=false]:translate-y-full'
            : 'data-[active=false]:-translate-y-full',
            $buttonVariants[$variant] ?? $buttonVariants['secondary'],
            $buttonSizes[$size] ?? $buttonSizes['icon-sm'],
            'rounded-full inline-flex items-center justify-center [&_svg]:pointer-events-none [&_svg:not([class*=size-])]:size-4',
        ])
    }}
>
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            @class(['rotate-180' => $direction === 'start'])
            aria-hidden="true"
        >
            <path d="M12 5v14" />
            <path d="m19 12-7 7-7-7" />
        </svg>
        <span class="sr-only"> {{ $direction === 'end' ? 'Scroll to end' : 'Scroll to start' }} </span>
    @endif
</button>
