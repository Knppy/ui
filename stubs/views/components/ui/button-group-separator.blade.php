@props([
    'orientation' => 'vertical',
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('bg-input relative m-0! self-stretch data-[orientation=vertical]:h-auto')
        ->add($attributes['class'] ?? '');
@endphp

<x-ui.separator
    data-slot="button-group-separator"
    :orientation="$orientation"
    {{ $attributes->twMerge(['class' => $classes]) }}
/>
