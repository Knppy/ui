@props([
    'open' => false,
    'delayDuration' => 700,
])

<div
    data-slot="tooltip"
    x-data="{
        open: @js((bool) $open),
        _delayDuration: @js((int) $delayDuration),
        _openTimer: null,
        _closeTimer: null,
        _openTooltip() {
            clearTimeout(this._closeTimer);
            this._openTimer = setTimeout(() => { this.open = true }, this._delayDuration);
        },
        _closeTooltip() {
            clearTimeout(this._openTimer);
            this._closeTimer = setTimeout(() => { this.open = false }, 100);
        },
    }"
    x-id="['ui-tooltip']"
    {{ $attributes }}
>
    {{ $slot }}
</div>
