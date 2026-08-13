@props([
    'value' => '',
    'disabled' => false,
    'indicator' => 'check',
])

@php
    use Illuminate\Support\Js;

    $indicator = in_array($indicator, ['check', 'checkbox', 'radio'], true) ? $indicator : 'check';
    $jsVal = Js::from((string) $value)->toHtml();
@endphp

<div
    role="option"
    tabindex="-1"
    :id="$id('ui-combobox-opt', {!! $jsVal !!})"
    x-init="registerOption({!! $jsVal !!}, $el.querySelector('[data-slot=combobox-item-label]')?.textContent.trim() ?? {!! $jsVal !!})"
    x-show="visible.some((o) => o.value === {!! $jsVal !!})"
    @if (! $disabled)
        @click="select({!! $jsVal !!})"
    @endif
    @mouseenter="activeValue = {!! $jsVal !!}"
    :aria-selected="isSelected({!! $jsVal !!})"
    :data-active="activeValue === {!! $jsVal !!}"
    @if ($disabled) data-disabled aria-disabled="true" @endif
    data-slot="combobox-item"
    {{ $attributes->twMerge("relative flex w-full cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none data-[active=true]:bg-accent data-[active=true]:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4") }}
>
    @switch ($indicator)
        @case ('checkbox')
            <span
                class="border-input flex size-4 shrink-0 items-center justify-center rounded-[4px] border transition-colors"
                :class="isSelected({!! $jsVal !!}) && 'bg-primary border-primary text-primary-foreground'"
            >
                <x-lucide-check
                    class="size-3"
                    x-bind:class="isSelected({!! $jsVal !!}) ? 'opacity-100' : 'opacity-0'"
                    aria-hidden="true"
                />
            </span>
            @break
        @case ('radio')
            <span
                class="border-input flex size-4 shrink-0 items-center justify-center rounded-full border transition-colors"
                :class="isSelected({!! $jsVal !!}) && 'border-primary'"
            >
                <span
                    class="bg-primary size-2 rounded-full transition-opacity"
                    :class="isSelected({!! $jsVal !!}) ? 'opacity-100' : 'opacity-0'"
                ></span>
            </span>
            @break
        @default
            <x-lucide-check
                class="size-4"
                x-bind:class="isSelected({!! $jsVal !!}) ? 'opacity-100' : 'opacity-0'"
                aria-hidden="true"
            />
    @endswitch

    <span data-slot="combobox-item-label">{{ $slot->isNotEmpty() ? $slot : $value }}</span>
</div>
