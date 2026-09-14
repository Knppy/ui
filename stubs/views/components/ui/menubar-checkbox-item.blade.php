@props(['checked' => false, 'disabled' => false])

<div
    x-data="uiMenubarCheckbox(@js($checked))"
    x-modelable="checked"
    x-on:click="if (! @js($disabled)) toggleChecked()"
    x-bind:aria-checked="checked"
    x-bind:data-state="checked ? 'checked' : 'unchecked'"
    role="menuitemcheckbox"
    tabindex="{{ $disabled ? '-1' : '0' }}"
    data-slot="menubar-checkbox-item"
    @if ($disabled) data-disabled aria-disabled="true" @endif
    {{ $attributes->except('disabled')->twMerge(['class' => "relative flex cursor-default items-center gap-2 rounded-xs py-1.5 pr-2 pl-8 text-sm outline-hidden select-none focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"]) }}
>
    <span aria-hidden="true" class="pointer-events-none absolute left-2 flex size-3.5 items-center justify-center">
        <svg
            x-show="checked"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="size-4"
        >
            <path d="m5 12 4 4L19 6" />
        </svg>
    </span>
    {{ $slot }}
</div>
