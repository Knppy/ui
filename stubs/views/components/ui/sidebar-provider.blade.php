@props(['defaultOpen' => true])

<div
    x-data="uiSidebar(@js($defaultOpen))"
    x-modelable="open"
    x-on:keydown.window="handleShortcut($event)"
    x-bind:data-state="state"
    data-slot="sidebar-wrapper"
    {{ $attributes->except('style')->twMerge(['class' => 'group/sidebar-wrapper relative flex min-h-svh w-full overflow-hidden bg-sidebar']) }}
    style="--sidebar-width: 16rem; --sidebar-width-mobile: 18rem; --sidebar-width-icon: 3rem; {{ $attributes->get('style') }}"
>
    {{ $slot }}
</div>
