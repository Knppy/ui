@props([
    'value' => null,
    'disabled' => false,
])

<button
    type="button"
    data-slot="tabs-trigger"
    role="tab"
    :id="$id('ui-tabs-trigger', @js($value))"
    :aria-controls="$id('ui-tabs-panel', @js($value))"
    :aria-selected="isActive(@js($value))"
    :data-state="isActive(@js($value)) ? 'active' : 'inactive'"
    :tabindex="isActive(@js($value)) ? 0 : -1"
    @click="activate(@js($value))"
    @if ($disabled) disabled @endif
    {{ $attributes->twMerge(
        'relative inline-flex h-[calc(100%-1px)] flex-1 items-center justify-center gap-1.5 rounded-md border border-transparent px-2 py-1 text-sm font-medium whitespace-nowrap transition-all'
        .' text-foreground/60 hover:text-foreground dark:text-muted-foreground dark:hover:text-foreground'
        .' focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 focus-visible:outline-ring outline-none'
        .' disabled:pointer-events-none disabled:opacity-50'
        .' group-data-[orientation=vertical]/tabs:w-full group-data-[orientation=vertical]/tabs:justify-start'
        .' data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm'
        .' dark:data-[state=active]:border-input dark:data-[state=active]:bg-input/30 dark:data-[state=active]:text-foreground'
        .' [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4'
        .' after:absolute after:bg-foreground after:opacity-0 after:transition-opacity'
        .' group-data-[orientation=horizontal]/tabs:after:inset-x-0 group-data-[orientation=horizontal]/tabs:after:bottom-[-5px] group-data-[orientation=horizontal]/tabs:after:h-0.5'
        .' group-data-[orientation=vertical]/tabs:after:inset-y-0 group-data-[orientation=vertical]/tabs:after:-right-1 group-data-[orientation=vertical]/tabs:after:w-0.5'
        .' [data-variant=line]_&:bg-transparent [data-variant=line]_&:data-[state=active]:bg-transparent [data-variant=line]_&:data-[state=active]:shadow-none [data-variant=line]_&:data-[state=active]:after:opacity-100'
    ) }}
>
    {{ $slot }}
</button>
