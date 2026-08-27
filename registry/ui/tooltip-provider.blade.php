@props([
    'delayDuration' => 700,
])

<div data-slot="tooltip-provider" x-data="{ _providerDelayDuration: @js((int) $delayDuration) }" {{ $attributes }}>
    {{ $slot }}
</div>
