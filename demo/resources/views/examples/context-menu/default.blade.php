<x-ui.context-menu>
    <x-ui.context-menu-trigger class="flex aspect-video w-full max-w-xs items-center justify-center rounded-xl border border-dashed text-sm">
        <span class="hidden pointer-fine:inline-block"> Right click here </span>
        <span class="hidden pointer-coarse:inline-block"> Long press here </span>
    </x-ui.context-menu-trigger>
    <x-ui.context-menu-content class="w-48">
        <x-ui.context-menu-group>
            <x-ui.context-menu-item>
                Back
                <x-ui.context-menu-shortcut>⌘[</x-ui.context-menu-shortcut>
            </x-ui.context-menu-item>
            <x-ui.context-menu-item disabled>
                Forward
                <x-ui.context-menu-shortcut>⌘]</x-ui.context-menu-shortcut>
            </x-ui.context-menu-item>
            <x-ui.context-menu-item>
                Reload
                <x-ui.context-menu-shortcut>⌘R</x-ui.context-menu-shortcut>
            </x-ui.context-menu-item>
            <x-ui.context-menu-sub>
                <x-ui.context-menu-sub-trigger>More Tools</x-ui.context-menu-sub-trigger>
                <x-ui.context-menu-sub-content class="w-44">
                    <x-ui.context-menu-group>
                        <x-ui.context-menu-item>Save Page...</x-ui.context-menu-item>
                        <x-ui.context-menu-item>Create Shortcut...</x-ui.context-menu-item>
                        <x-ui.context-menu-item>Name Window...</x-ui.context-menu-item>
                    </x-ui.context-menu-group>
                    <x-ui.context-menu-separator />
                    <x-ui.context-menu-group>
                        <x-ui.context-menu-item>Developer Tools</x-ui.context-menu-item>
                    </x-ui.context-menu-group>
                    <x-ui.context-menu-separator />
                    <x-ui.context-menu-group>
                        <x-ui.context-menu-item variant="destructive">Delete</x-ui.context-menu-item>
                    </x-ui.context-menu-group>
                </x-ui.context-menu-sub-content>
            </x-ui.context-menu-sub>
        </x-ui.context-menu-group>
        <x-ui.context-menu-separator />
        <x-ui.context-menu-group>
            <x-ui.context-menu-checkbox-item checked> Show Bookmarks </x-ui.context-menu-checkbox-item>
            <x-ui.context-menu-checkbox-item>Show Full URLs</x-ui.context-menu-checkbox-item>
        </x-ui.context-menu-group>
        <x-ui.context-menu-separator />
        <x-ui.context-menu-group>
            <x-ui.context-menu-radio-group value="pedro">
                <x-ui.context-menu-label>People</x-ui.context-menu-label>
                <x-ui.context-menu-radio-item value="pedro"> Pedro Duarte </x-ui.context-menu-radio-item>
                <x-ui.context-menu-radio-item value="colm">Colm Tuite</x-ui.context-menu-radio-item>
            </x-ui.context-menu-radio-group>
        </x-ui.context-menu-group>
    </x-ui.context-menu-content>
</x-ui.context-menu>
