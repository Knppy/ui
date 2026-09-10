@props(['value'])

<button
    type="button"
    x-on:click="toggle(@js($value))"
    x-bind:aria-pressed="isPressed(@js($value))"
    x-bind:data-state="isPressed(@js($value)) ? 'on' : 'off'"
    data-slot="toggle-group-item"
    data-value="{{ $value }}"
    {{ $attributes->twMerge(['class' => "inline-flex h-9 w-auto min-w-0 shrink-0 items-center justify-center gap-2 rounded-md bg-transparent px-3 text-sm font-medium whitespace-nowrap transition-[color,box-shadow] outline-none hover:bg-muted hover:text-muted-foreground focus:z-10 focus-visible:z-10 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 data-[state=on]:bg-accent data-[state=on]:text-accent-foreground group-data-[size=sm]/toggle-group:h-8 group-data-[size=sm]/toggle-group:px-1.5 group-data-[size=lg]/toggle-group:h-10 group-data-[size=lg]/toggle-group:px-2.5 group-data-[variant=outline]/toggle-group:border group-data-[variant=outline]/toggle-group:border-input group-data-[variant=outline]/toggle-group:shadow-xs group-data-[spacing=0]/toggle-group:rounded-none group-data-[spacing=0]/toggle-group:border-l-0 group-data-[spacing=0]/toggle-group:shadow-none group-data-[spacing=0]/toggle-group:first:rounded-l-md group-data-[spacing=0]/toggle-group:first:border-l group-data-[spacing=0]/toggle-group:last:rounded-r-md [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"]) }}
>
    {{ $slot }}
</button>
