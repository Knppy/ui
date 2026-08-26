@props([
    'viewport' => true,
])

<nav
    data-slot="navigation-menu"
    x-data="uiNavigationMenu()"
    :data-viewport="@js($viewport)"
    @keydown.escape.window="close(0)"
    {{ $attributes->twMerge('group/navigation-menu relative flex max-w-max flex-1 items-center justify-center') }}
>
    {{ $slot }}
</nav>
