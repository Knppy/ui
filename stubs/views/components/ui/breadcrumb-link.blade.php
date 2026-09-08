@props([
    'is' => null,
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('transition-colors hover:text-foreground')
        ->add($attributes['class'] ?? '');

    $tag = $is ?? 'a';
@endphp

<{{ $tag }} data-slot="breadcrumb-link" {{ $attributes->twMerge(['class' => $classes]) }}>{{ $slot }}</{{ $tag }}>
