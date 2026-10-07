<span
    data-slot="attachment-title"
    {{ $attributes->twMerge(['class' => 'block max-w-full min-w-0 truncate font-medium group-data-[state=processing]/attachment:shimmer group-data-[state=uploading]/attachment:shimmer']) }}
>{{ $slot }}</span>
