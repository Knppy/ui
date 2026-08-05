@props(['href' => '#'])

<a href="{{ $href }}" data-slot="breadcrumb-link" {{ $attributes->twMerge('transition-colors hover:text-foreground') }}>
    {{ $slot }}
</a>
