@props(['withHandle' => false])

<div
    role="separator"
    tabindex="0"
    aria-valuemin="0"
    aria-valuemax="100"
    x-bind:aria-orientation="orientation"
    x-on:pointerdown="startResize($event)"
    x-on:keydown="resizeWithKeyboard($event)"
    data-slot="resizable-handle"
    {{ $attributes->twMerge(['class' => 'relative flex w-px shrink-0 touch-none items-center justify-center bg-border outline-none after:absolute after:inset-y-0 after:left-1/2 after:w-1 after:-translate-x-1/2 focus-visible:ring-1 focus-visible:ring-ring focus-visible:ring-offset-1 data-[orientation=vertical]:h-px data-[orientation=vertical]:w-full data-[orientation=vertical]:after:inset-x-0 data-[orientation=vertical]:after:inset-y-auto data-[orientation=vertical]:after:top-1/2 data-[orientation=vertical]:after:h-1 data-[orientation=vertical]:after:w-full data-[orientation=vertical]:after:translate-x-0 data-[orientation=vertical]:after:-translate-y-1/2 [&[data-orientation=vertical]>div]:rotate-90']) }}
    x-bind:data-orientation="orientation"
>
    @if ($withHandle)
        <div class="bg-border z-10 flex h-4 w-3 items-center justify-center rounded-xs border">
            <svg class="size-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <circle cx="9" cy="12" r="1" />
                <circle cx="9" cy="5" r="1" />
                <circle cx="9" cy="19" r="1" />
                <circle cx="15" cy="12" r="1" />
                <circle cx="15" cy="5" r="1" />
                <circle cx="15" cy="19" r="1" />
            </svg>
        </div>
    @endif
</div>
