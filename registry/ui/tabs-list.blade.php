@props([
    'variant' => 'default',
])

<div
    data-slot="tabs-list"
    data-variant="{{ $variant }}"
    role="tablist"
    :aria-orientation="orientation"
    {{ $attributes->twMerge(
        'group/tabs-list inline-flex w-fit items-center justify-center rounded-lg p-[3px] text-muted-foreground'
        .' group-data-[orientation=horizontal]/tabs:h-9 group-data-[orientation=vertical]/tabs:h-fit group-data-[orientation=vertical]/tabs:flex-col'
        .($variant === 'default' ? ' bg-muted' : ' gap-1 bg-transparent rounded-none')
    ) }}
>
    {{ $slot }}
</div>
