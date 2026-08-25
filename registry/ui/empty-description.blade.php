<div
    data-slot="empty-description"
    {{ $attributes->twMerge('text-sm/relaxed text-muted-foreground [&>a]:underline [&>a]:underline-offset-4 [&>a:hover]:text-primary') }}
>
    {{ $slot }}
</div>
