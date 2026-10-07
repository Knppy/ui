@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('flex flex-col gap-6 rounded-xl border bg-card py-6 text-card-foreground shadow-sm')
        ->add($attributes['class'] ?? '');
@endphp

<div data-slot="card" {{ $attributes->twMerge(['class' => $classes]) }}>{{ $slot }}</div>
