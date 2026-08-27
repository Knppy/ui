@props([
    'value' => null,
    'orientation' => 'horizontal',
])

<div
    data-slot="tabs"
    data-orientation="{{ $orientation }}"
    x-data="{
        active: @js($value),
        orientation: @js($orientation),
        activate(v) { this.active = v; },
        isActive(v) { return this.active === v; },
    }"
    @keydown="
        if (['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'].includes($event.key)) {
            $uiNav($event, {
                selector: '[data-slot=tabs-trigger]:not([disabled])',
                orientation: orientation,
                loop: true,
                requireMatch: true,
            });
        }
    "
    {{ $attributes->twMerge('group/tabs flex gap-2 data-[orientation=horizontal]:flex-col data-[orientation=vertical]:flex-row') }}
>
    {{ $slot }}
</div>
