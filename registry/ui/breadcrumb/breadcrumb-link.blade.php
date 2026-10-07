@props([
    'is' => 'a',
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('transition-colors hover:text-foreground')
        ->add($attributes['class'] ?? '');
@endphp

<{{ $is }} data-slot="breadcrumb-link" {{ $attributes->twMerge(['class' => $classes]) }}>{{ $slot }}</{{ $is }}>
