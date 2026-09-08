@props([
    'side' => 'bottom',
    'align' => 'end',
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('absolute z-10 flex w-fit shrink-0 items-center justify-center gap-1 rounded-full bg-muted px-1.5 py-0.5 text-sm ring-3 ring-card has-[button]:p-0')
        ->add($side === 'top' ? 'top-0 -translate-y-3/4' : 'bottom-0 translate-y-3/4')
        ->add($align === 'start' ? 'left-3' : 'right-3')
        ->add($attributes['class'] ?? '');
@endphp

<div
    data-slot="bubble-reactions"
    data-align="{{ $align }}"
    data-side="{{ $side }}"
    {{ $attributes->twMerge(['class' => $classes]) }}
>
    {{ $slot }}
</div>
