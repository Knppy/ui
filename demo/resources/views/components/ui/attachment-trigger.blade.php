@props(['is' => 'button'])

<{{ $is }}
    data-slot="attachment-trigger"
    @if ($is === 'button') type="button" @endif
    {{ $attributes->twMerge(['class' => 'absolute inset-0 z-10 outline-none']) }}
    >{{ $slot }}</{{ $is }}
>
