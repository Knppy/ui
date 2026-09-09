<tr
    data-slot="table-row"
    {{ $attributes->twMerge(['class' => 'border-b transition-colors hover:bg-muted/50 has-aria-expanded:bg-muted/50 data-[state=selected]:bg-muted']) }}
>
    {{ $slot }}
</tr>
