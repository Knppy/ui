@props(['variant' => 'default'])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('flex shrink-0 items-center justify-center gap-2 group-has-[[data-slot=item-description]]/item:translate-y-0.5 group-has-[[data-slot=item-description]]/item:self-start [&_svg]:pointer-events-none')
        ->add(match ($variant) {
            'icon' => "size-8 rounded-sm border bg-muted [&_svg:not([class*='size-'])]:size-4",
            'image' => 'size-10 overflow-hidden rounded-sm [&_img]:size-full [&_img]:object-cover',
            default => 'bg-transparent',
        })
        ->add($attributes['class'] ?? '');
@endphp

<div data-slot="item-media" data-variant="{{ $variant }}" {{ $attributes->twMerge(['class' => $classes]) }}>
    {{ $slot }}
</div>
