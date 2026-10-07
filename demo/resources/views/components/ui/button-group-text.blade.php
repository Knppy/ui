@props([
    'is' => 'div',
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('flex items-center gap-2 rounded-md border bg-muted px-4 text-sm font-medium shadow-xs [&_svg]:pointer-events-none [&_svg:not([class*=\'size-\'])]:size-4')
        ->add($attributes['class'] ?? '');
@endphp

<{{$is}} {{ $attributes->twMerge(['class' => $classes]) }}>{{ $slot }}</{{$is}}>
