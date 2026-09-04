@props([
    'orientation' => 'vertical',
])

@php
    use Knppy\Ui\Facades\Ui;

    $base = 'bg-input relative m-0! self-stretch data-[orientation=vertical]:h-auto';

    $classes = Ui::classes()
        ->add($base)
        ->add($attributes['class'] ?? '');
@endphp

<x-ui.separator
    data-slot="button-group-separator"
    :orientation="$orientation"
    {{ $attributes->twMerge(['class' => $classes]) }}
/>
