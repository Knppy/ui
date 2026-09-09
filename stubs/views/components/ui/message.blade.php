@props(['align' => 'start'])

<div
    data-slot="message"
    data-align="{{ $align }}"
    {{ $attributes->twMerge(['class' => 'group/message relative flex w-full min-w-0 gap-2 text-sm data-[align=end]:flex-row-reverse']) }}
>
    {{ $slot }}
</div>
