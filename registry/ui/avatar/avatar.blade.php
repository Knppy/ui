@props(['size' => 'default'])

<span
    x-data="uiAvatar"
    data-slot="avatar"
    data-size="{{ $size }}"
    {{ $attributes->twMerge(['class' => 'group/avatar relative flex size-8 shrink-0 overflow-hidden rounded-full select-none data-[size=lg]:size-10 data-[size=sm]:size-6']) }}
>
    {{ $slot }}
</span>
