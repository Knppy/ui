@props([
    'orientation' => 'horizontal',
    'decorative' => true,
])

@php
    use Knppy\Ui\Facades\Ui;

    $base = 'bg-border shrink-0 data-[orientation=horizontal]:h-px data-[orientation=horizontal]:w-full data-[orientation=vertical]:h-full data-[orientation=vertical]:w-px';

    $classes = Ui::classes()
        ->add($base);
@endphp

<div
    data-slot="separator"
    role="{{ $decorative ? 'none' : 'separator' }}"
    @unless ($decorative) aria-orientation="{{ $orientation }}" @endunless
    data-orientation="{{ $orientation }}"
    {{ $attributes->merge(['class' => $classes]) }}
></div>
