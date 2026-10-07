@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('col-start-2 grid justify-items-start gap-1 text-sm text-muted-foreground [&_p]:leading-relaxed')
        ->add($attributes['class'] ?? '');
@endphp

<div data-slot="alert-description" {{ $attributes->twMerge(['class' => $classes]) }}>{{ $slot }}</div>
