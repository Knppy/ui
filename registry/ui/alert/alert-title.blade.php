@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('col-start-2 line-clamp-1 min-h-4 font-medium tracking-tight')
        ->add($attributes['class'] ?? '');
@endphp

<div data-slot="alert-title" {{ $attributes->twMerge(['class' => $classes]) }}>{{ $slot }}</div>
