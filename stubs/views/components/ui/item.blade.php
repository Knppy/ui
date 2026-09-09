@props([
    'is' => 'div',
    'variant' => 'default',
    'size' => 'default',
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('group/item flex flex-wrap items-center rounded-md border border-transparent text-sm transition-colors duration-100 outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 [a]:transition-colors [a]:hover:bg-accent/50')
        ->add(match ($variant) {
            'outline' => 'border-border',
            'muted' => 'bg-muted/50',
            default => 'bg-transparent',
        })
        ->add($size === 'sm' ? 'gap-2.5 px-4 py-3' : 'gap-4 p-4')
        ->add($attributes['class'] ?? '');
@endphp

<{{ $is }}
    data-slot="item"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    {{ $attributes->twMerge(['class' => $classes]) }}
    >{{ $slot }}</{{ $is }}
>
