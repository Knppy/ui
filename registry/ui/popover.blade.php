@props(['open' => false])

<div data-slot="popover" x-data="{ open: @js((bool) $open), _anchor: null }" x-id="['ui-popover']" {{ $attributes }}>
    {{ $slot }}
</div>
