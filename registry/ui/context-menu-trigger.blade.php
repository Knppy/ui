<span data-slot="context-menu-trigger" @contextmenu.prevent="openAt($event)" {{ $attributes->twMerge('inline-block') }}>
    {{ $slot }}
</span>
