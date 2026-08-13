<textarea
    data-slot="input-group-control"
    {{ $attributes->twMerge('flex-1 resize-none rounded-none border-0 bg-transparent py-3 shadow-none focus-visible:ring-0 dark:bg-transparent w-full min-w-0 px-3 text-base outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50 md:text-sm') }}
>{{ $slot }}</textarea>
