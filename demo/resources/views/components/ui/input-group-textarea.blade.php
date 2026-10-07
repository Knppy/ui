<textarea
    data-slot="input-group-control"
    {{ $attributes->twMerge(['class' => 'field-sizing-content min-h-16 min-w-0 flex-1 resize-none rounded-none border-0 bg-transparent px-3 py-3 text-base shadow-none outline-none placeholder:text-muted-foreground focus-visible:ring-0 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm dark:bg-transparent']) }}
>{{ $slot }}</textarea>