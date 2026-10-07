<x-ui.dialog>
    <form>
        <x-ui.dialog-trigger><x-ui.button variant="outline">Open Dialog</x-ui.button></x-ui.dialog-trigger>
        <x-ui.dialog-content class="sm:max-w-sm">
            <x-ui.dialog-header>
                <x-ui.dialog-title>Edit profile</x-ui.dialog-title>
                <x-ui.dialog-description>
                    Make changes to your profile here. Click save when you&apos;re done.
                </x-ui.dialog-description>
            </x-ui.dialog-header>
            <x-ui.field-group>
                <x-ui.field>
                    <x-ui.label for="name-1">Name</x-ui.label>
                    <x-ui.input id="name-1" name="name" value="Pedro Duarte" />
                </x-ui.field>
                <x-ui.field>
                    <x-ui.label for="username-1">Username</x-ui.label>
                    <x-ui.input id="username-1" name="username" value="@peduarte" />
                </x-ui.field>
            </x-ui.field-group>
            <x-ui.dialog-footer>
                <x-ui.dialog-close><x-ui.button variant="outline">Cancel</x-ui.button></x-ui.dialog-close>
                <x-ui.button type="submit">Save changes</x-ui.button>
            </x-ui.dialog-footer>
        </x-ui.dialog-content>
    </form>
</x-ui.dialog>
