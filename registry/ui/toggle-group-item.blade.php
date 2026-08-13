@props([
    'value' => null,
    'disabled' => false,
])

@php
    $base = "inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium hover:bg-muted hover:text-muted-foreground disabled:pointer-events-none disabled:opacity-50 data-[state=on]:bg-accent data-[state=on]:text-accent-foreground [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 [&_svg]:shrink-0 focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] outline-none transition-[color,box-shadow] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive whitespace-nowrap";

    $variant = 'group-data-[variant=outline]/toggle-group:border group-data-[variant=outline]/toggle-group:border-input group-data-[variant=outline]/toggle-group:shadow-xs group-data-[variant=outline]/toggle-group:hover:bg-accent group-data-[variant=outline]/toggle-group:hover:text-accent-foreground';

    $size = 'group-data-[size=default]/toggle-group:h-9 group-data-[size=default]/toggle-group:px-2 group-data-[size=default]/toggle-group:min-w-9 '
        .'group-data-[size=sm]/toggle-group:h-8 group-data-[size=sm]/toggle-group:px-1.5 group-data-[size=sm]/toggle-group:min-w-8 '
        .'group-data-[size=lg]/toggle-group:h-10 group-data-[size=lg]/toggle-group:px-2.5 group-data-[size=lg]/toggle-group:min-w-10';

    // spacing=0: items are joined — no rounding except first/last, collapsed borders
    // when spacing>0, none of these fire and the base rounded-md from $base remains intact
    $shape = 'min-w-0 flex-1 shrink-0 focus:z-10 focus-visible:z-10 '
        .'group-data-[spacing=0]/toggle-group:rounded-none group-data-[spacing=0]/toggle-group:shadow-none '
        .'group-data-[spacing=0][data-orientation=horizontal]/toggle-group:first:rounded-s-md group-data-[spacing=0][data-orientation=horizontal]/toggle-group:last:rounded-e-md group-data-[spacing=0][data-orientation=horizontal]/toggle-group:not-first:border-s-0 '
        .'group-data-[spacing=0][data-orientation=vertical]/toggle-group:w-full group-data-[spacing=0][data-orientation=vertical]/toggle-group:first:rounded-t-md group-data-[spacing=0][data-orientation=vertical]/toggle-group:last:rounded-b-md group-data-[spacing=0][data-orientation=vertical]/toggle-group:not-first:border-t-0';

    $classes = "$base $variant $size $shape";
@endphp

<button
    type="button"
    data-slot="toggle-group-item"
    data-value="{{ $value }}"
    @click="toggle(@js($value))"
    @focus="rovingValue = @js($value)"
    :data-state="isOn(@js($value)) ? 'on' : 'off'"
    :aria-pressed="isOn(@js($value))"
    :tabindex="rovingValue === @js($value) ? 0 : -1"
    @if ($disabled) disabled @endif
    {{ $attributes->twMerge($classes) }}
>
    {{ $slot }}
</button>
