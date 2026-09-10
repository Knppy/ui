<x-ui.sheet>
    <x-ui.sheet-trigger><x-ui.button variant="outline">Open</x-ui.button></x-ui.sheet-trigger>
    <x-ui.sheet-content>
        <x-ui.sheet-header>
            <x-ui.sheet-title>Edit profile</x-ui.sheet-title>
            <x-ui.sheet-description>
                Make changes to your profile here. Click save when you&apos;re done.
            </x-ui.sheet-description>
        </x-ui.sheet-header>
        <div class="grid flex-1 auto-rows-min gap-6 px-4">
            <div class="grid gap-3">
                <x-ui.label for="sheet-demo-name">Name</x-ui.label>
                <x-ui.input id="sheet-demo-name" value="Pedro Duarte" />
            </div>
            <div class="grid gap-3">
                <x-ui.label for="sheet-demo-username">Username</x-ui.label>
                <x-ui.input id="sheet-demo-username" value="@peduarte" />
            </div>
        </div>
        <x-ui.sheet-footer>
            <x-ui.button type="submit">Save changes</x-ui.button>
            <x-ui.sheet-close><x-ui.button variant="outline">Close</x-ui.button></x-ui.sheet-close>
        </x-ui.sheet-footer>
    </x-ui.sheet-content>
</x-ui.sheet>
