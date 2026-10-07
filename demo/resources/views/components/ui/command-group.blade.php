@props(['heading' => null])

<div
    role="group"
    @if ($heading) aria-label="{{ $heading }}" @endif
    data-slot="command-group"
    {{ $attributes->twMerge(['class' => 'overflow-hidden p-1 text-foreground']) }}
>
    @if ($heading)
        <div class="text-muted-foreground px-2 py-1.5 text-xs font-medium">{{ $heading }}</div>
    @endif
    {{ $slot }}
</div>
