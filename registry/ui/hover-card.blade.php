@props([
    'open' => false,
    'openDelay' => 700,
    'closeDelay' => 300,
])

<div
    data-slot="hover-card"
    x-data="{
        open: @js((bool) $open),
        _openDelay: @js((int) $openDelay),
        _closeDelay: @js((int) $closeDelay),
        _openTimer: null,
        _closeTimer: null,
        _openCard() {
            clearTimeout(this._closeTimer);
            this._openTimer = setTimeout(() => { this.open = true }, this._openDelay);
        },
        _closeCard() {
            clearTimeout(this._openTimer);
            this._closeTimer = setTimeout(() => { this.open = false }, this._closeDelay);
        },
    }"
    x-id="['ui-hover-card']"
    {{ $attributes }}
>
    {{ $slot }}
</div>
