@props(['orientation' => 'vertical'])

@php
    $base = 'group/field flex w-full gap-3 data-[invalid=true]:text-destructive';

    $orientations = [
        'vertical' => 'flex-col [&>*]:w-full [&>.sr-only]:w-auto',
        'horizontal' => [
            'flex-row items-center',
            '[&>[data-slot=field-label]]:flex-auto',
            'has-[>[data-slot=field-content]]:items-start has-[>[data-slot=field-content]]:[&>[role=checkbox],[role=radio]]:mt-px',
        ],
        'responsive' => [
            'flex-col @md/field-group:flex-row @md/field-group:items-center [&>*]:w-full @md/field-group:[&>*]:w-auto [&>.sr-only]:w-auto',
            '@md/field-group:[&>[data-slot=field-label]]:flex-auto',
            '@md/field-group:has-[>[data-slot=field-content]]:items-start @md/field-group:has-[>[data-slot=field-content]]:[&>[role=checkbox],[role=radio]]:mt-px',
        ],
    ];

    $classes = [
        $base,
        $orientations[$orientation] ?? $orientations['vertical'],
    ];
@endphp

<div
    role="group"
    data-slot="field"
    data-orientation="{{ $orientation }}"
    x-data="{}"
    x-ui-field
    {{ $attributes->twMerge($classes) }}
>
    {{ $slot }}
</div>
