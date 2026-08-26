@props([])

<button
    type="button"
    data-slot="navigation-menu-trigger"
    x-init="registerItem(_itemValue, $el, null)"
    :data-state="isOpen(_itemValue) ? 'open' : 'closed'"
    :aria-expanded="isOpen(_itemValue)"
    @click="toggle(_itemValue)"
    @mouseenter="open(_itemValue)"
    @keydown.right.prevent="moveTrigger(_itemValue, 1)"
    @keydown.left.prevent="moveTrigger(_itemValue, -1)"
    {{ $attributes->twMerge('group inline-flex h-9 w-max items-center justify-center rounded-md bg-background px-4 py-2 text-sm font-medium transition-[color,box-shadow] outline-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-1 disabled:pointer-events-none disabled:opacity-50 data-[state=open]:bg-accent/50 data-[state=open]:text-accent-foreground data-[state=open]:hover:bg-accent data-[state=open]:focus:bg-accent') }}
>
    {{ $slot }}
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="12"
        height="12"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="relative top-px ml-1 size-3 transition duration-300 group-data-[state=open]:rotate-180"
        aria-hidden="true"
    >
        <path d="m6 9 6 6 6-6" />
    </svg>
</button>
