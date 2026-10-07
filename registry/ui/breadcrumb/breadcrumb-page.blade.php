@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('text-foreground font-normal')
        ->add($attributes['class'] ?? '');
@endphp

<span
    data-slot="breadcrumb-page"
    role="link"
    aria-disabled="true"
    aria-current="page"
    {{ $attributes->twMerge(['class' => $classes]) }}
>{{ $slot }}</span>
