@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('[&>svg]:size-3.5')
        ->add($attributes['class'] ?? '');
@endphp

<li
    data-slot="breadcrumb-separator"
    role="presentation"
    aria-hidden="true"
    {{ $attributes->twMerge(['class' => $classes]) }}
>
    @if ($slot->isEmpty())
        <x-lucide-chevron-right />
    @else
        {{ $slot }}
    @endif
</li>
