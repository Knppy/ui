@props(['variant' => 'default'])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('mb-2 flex shrink-0 items-center justify-center [&_svg]:pointer-events-none [&_svg]:shrink-0')
        ->add($variant === 'icon' ? "flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground [&_svg:not([class*='size-'])]:size-6" : 'bg-transparent')
        ->add($attributes['class'] ?? '');
@endphp

<div data-slot="empty-icon" data-variant="{{ $variant }}" {{ $attributes->twMerge(['class' => $classes]) }}>
    {{ $slot }}
</div>
