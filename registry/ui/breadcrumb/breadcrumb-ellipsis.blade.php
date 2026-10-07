@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('flex size-9 items-center justify-center')
        ->add($attributes['class'] ?? '');
@endphp

<span
    data-slot="breadcrumb-ellipsis"
    role="presentation"
    aria-hidden="true"
    {{ $attributes->twMerge(['class' => $classes]) }}
>
    <x-lucide-ellipsis class="size-4" />
    <span class="sr-only">{{ __('More') }}</span>
</span>
