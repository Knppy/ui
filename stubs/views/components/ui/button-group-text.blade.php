@props([
    'is' => null,
])

@php
    use Knppy\Ui\Facades\Ui;

    $base = 'flex items-center gap-2 rounded-md border bg-muted px-4 text-sm font-medium shadow-xs [&_svg]:pointer-events-none [&_svg:not([class*=\'size-\'])]:size-4';

    $classes = Ui::classes()
        ->add($base)
        ->add($attributes['class'] ?? '');

    $tag = $is ?? 'div';
@endphp

<{{$tag}} {{ $attributes->twMerge(['class' => $classes]) }}>{{ $slot }}</{{$tag}}>
