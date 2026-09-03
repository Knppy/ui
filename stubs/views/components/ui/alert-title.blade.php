@php
    use Knppy\Ui\Facades\Ui;

    $base = 'col-start-2 line-clamp-1 min-h-4 font-medium tracking-tight';

    $classes = Ui::classes()
        ->add($base);
@endphp

<div data-slot="alert-title" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</div>
