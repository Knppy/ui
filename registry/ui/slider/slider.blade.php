@props([
    'value' => 0,
    'min' => 0,
    'max' => 100,
    'step' => 1,
    'orientation' => 'horizontal',
])

<label
    x-data="uiSlider(@js($value), @js($min), @js($max), @js($step))"
    x-modelable="value"
    x-bind:data-disabled="$refs.input?.disabled || null"
    data-slot="slider"
    data-orientation="{{ $orientation }}"
    {{ $attributes->whereStartsWith(['x-model', 'wire:model']) }}
    {{ $attributes->only('class')->twMerge(['class' => 'relative flex w-full touch-none items-center select-none data-[disabled]:opacity-50 data-[orientation=vertical]:h-full data-[orientation=vertical]:min-h-44 data-[orientation=vertical]:w-auto data-[orientation=vertical]:flex-col']) }}
>
    <span
        aria-hidden="true"
        data-slot="slider-track"
        data-orientation="{{ $orientation }}"
        class="bg-muted relative grow overflow-hidden rounded-full data-[orientation=horizontal]:h-1.5 data-[orientation=horizontal]:w-full data-[orientation=vertical]:h-full data-[orientation=vertical]:w-1.5"
    >
        <span
            data-slot="slider-range"
            data-orientation="{{ $orientation }}"
            x-bind:style="@js($orientation) === 'vertical' ? `height: ${percentage}%` : `width: ${percentage}%`"
            class="bg-primary absolute data-[orientation=horizontal]:h-full data-[orientation=vertical]:bottom-0 data-[orientation=vertical]:w-full"
        ></span>
    </span>
    <input
        x-ref="input"
        x-model.number="value"
        type="range"
        min="{{ $min }}"
        max="{{ $max }}"
        step="{{ $step }}"
        value="{{ $value }}"
        orient="{{ $orientation }}"
        data-slot="slider-thumb"
        data-orientation="{{ $orientation }}"
        aria-orientation="{{ $orientation }}"
        {{ $attributes->whereDoesntStartWith(['x-model', 'wire:model'])->except('class')->twMerge(['class' => 'absolute inset-0 size-full cursor-pointer appearance-none bg-transparent outline-none disabled:pointer-events-none disabled:cursor-not-allowed [&::-moz-range-thumb]:size-4 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border [&::-moz-range-thumb]:border-primary [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:shadow-sm [&::-moz-range-thumb]:ring-ring/50 [&::-moz-range-thumb]:transition-[color,box-shadow] [&::-moz-range-thumb]:hover:ring-4 [&::-moz-range-thumb]:focus-visible:ring-4 [&::-moz-range-track]:bg-transparent [&::-webkit-slider-runnable-track]:bg-transparent [&::-webkit-slider-thumb]:size-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:border [&::-webkit-slider-thumb]:border-primary [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:shadow-sm [&::-webkit-slider-thumb]:ring-ring/50 [&::-webkit-slider-thumb]:transition-[color,box-shadow] [&::-webkit-slider-thumb]:hover:ring-4 focus-visible:[&::-webkit-slider-thumb]:ring-4 data-[orientation=vertical]:[direction:rtl] data-[orientation=vertical]:[writing-mode:vertical-lr]']) }}
    />
</label>
