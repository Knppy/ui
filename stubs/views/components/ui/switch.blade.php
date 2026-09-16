@props([
    'checked' => false,
    'size' => 'default',
])

<label
    x-data="uiSwitch(@js($checked))"
    x-modelable="open"
    x-bind:data-state="open ? 'checked' : 'unchecked'"
    data-slot="switch"
    data-size="{{ $size }}"
    {{ $attributes->whereStartsWith(['x-model', 'wire:model']) }}
    {{ $attributes->only('class')->twMerge(['class' => 'peer group/switch relative inline-flex shrink-0 cursor-pointer items-center rounded-full border border-transparent shadow-xs transition-all outline-none has-focus-visible:border-ring has-focus-visible:ring-[3px] has-focus-visible:ring-ring/50 has-disabled:cursor-not-allowed has-disabled:opacity-50 data-[size=default]:h-[1.15rem] data-[size=default]:w-8 data-[size=sm]:h-3.5 data-[size=sm]:w-6 data-[state=checked]:bg-primary data-[state=unchecked]:bg-input dark:data-[state=unchecked]:bg-input/80']) }}
>
    <input
        type="checkbox"
        role="switch"
        @checked($checked)
        x-model="open"
        class="sr-only"
        {{ $attributes->whereDoesntStartWith(['x-model', 'wire:model'])->except('class') }}
    />
    <span
        aria-hidden="true"
        data-slot="switch-thumb"
        x-bind:data-state="open ? 'checked' : 'unchecked'"
        class="bg-background dark:data-[state=checked]:bg-primary-foreground dark:data-[state=unchecked]:bg-foreground pointer-events-none block rounded-full ring-0 transition-transform group-data-[size=default]/switch:size-4 group-data-[size=sm]/switch:size-3 data-[state=checked]:translate-x-[calc(100%-2px)] data-[state=unchecked]:translate-x-0"
    ></span>
</label>
