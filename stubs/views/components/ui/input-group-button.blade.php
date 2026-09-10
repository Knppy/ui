@props([
    'type' => 'button',
    'variant' => 'ghost',
    'size' => 'xs',
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('flex items-center gap-2 text-sm shadow-none')
        ->add(match ($size) {
            'sm' => 'h-8 gap-1.5 rounded-md px-2.5 has-[>svg]:px-2.5',
            'icon-xs' => 'size-6 rounded-[calc(var(--radius)-5px)] p-0 has-[>svg]:p-0',
            'icon-sm' => 'size-8 p-0 has-[>svg]:p-0',
            default => "h-6 gap-1 rounded-[calc(var(--radius)-5px)] px-2 has-[>svg]:px-2 [&>svg:not([class*='size-'])]:size-3.5",
        });
@endphp

<x-ui.button
    :type="$type"
    :variant="$variant"
    :size="$size"
    dataSlot="input-group-button"
    {{ $attributes->twMerge(['class' => $classes]) }}
>
    {{ $slot }}
</x-ui.button>
