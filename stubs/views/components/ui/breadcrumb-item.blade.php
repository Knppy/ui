@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('inline-flex items-center gap-1.5')
        ->add($attributes['class'] ?? '');
@endphp

<li data-slot="breadcrumb-item" {{ $attributes->twMerge(['class' => $classes]) }}>{{ $slot }}</li>
