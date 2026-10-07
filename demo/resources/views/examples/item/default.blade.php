<div class="flex w-full max-w-md flex-col gap-6">
    <x-ui.item variant="outline">
        <x-ui.item-content>
            <x-ui.item-title>Basic item</x-ui.item-title>
            <x-ui.item-description> A simple item with title and description. </x-ui.item-description>
        </x-ui.item-content>
        <x-ui.item-actions>
            <x-ui.button variant="outline" size="sm"> Action </x-ui.button>
        </x-ui.item-actions>
    </x-ui.item>
    <x-ui.item is="a" href="#" variant="outline" size="sm">
        <x-ui.item-media>
            <x-lucide-badge-check class="size-5" />
        </x-ui.item-media>
        <x-ui.item-content>
            <x-ui.item-title>Your profile has been verified.</x-ui.item-title>
        </x-ui.item-content>
        <x-ui.item-actions>
            <x-lucide-chevron-right class="size-4" />
        </x-ui.item-actions>
    </x-ui.item>
</div>
