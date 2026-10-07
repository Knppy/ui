@props([
    'orientation' => 'horizontal',
    'decorative' => true,
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('bg-border shrink-0 data-[orientation=horizontal]:h-px data-[orientation=horizontal]:w-full data-[orientation=vertical]:h-full data-[orientation=vertical]:w-px')
        ->add($attributes['class'] ?? '');
@endphp

<div
    data-slot="{{ $attributes->get('data-slot', 'separator') }}"
    role="{{ $decorative ? 'none' : 'separator' }}"
    @unless ($decorative) aria-orientation="{{ $orientation }}" @endunless
    data-orientation="{{ $orientation }}"
    {{ $attributes->twMerge(['class' => $classes]) }}
></div>
