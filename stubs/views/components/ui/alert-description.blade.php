@php
    use Knppy\Ui\Facades\Ui;

    $base = 'col-start-2 grid justify-items-start gap-1 text-sm text-muted-foreground [&_p]:leading-relaxed';

    $classes = Ui::classes()
        ->add($base);
@endphp

<div data-slot="alert-description" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</div>
