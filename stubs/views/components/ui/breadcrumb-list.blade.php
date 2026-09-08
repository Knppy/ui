@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('text-muted-foreground flex flex-wrap items-center gap-1.5 text-sm break-words sm:gap-2.5')
        ->add($attributes['class'] ?? '');
@endphp

<ol data-slot="breadcrumb-list" {{ $attributes->twMerge(['class' => $classes]) }}>
    {{ $slot }}
</ol>
