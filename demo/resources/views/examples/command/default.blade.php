<div x-data="{ commandOpen: false }" class="flex flex-col gap-4">
    <x-ui.button variant="outline" class="w-fit" x-on:click="commandOpen = true">Open Menu</x-ui.button>
    <x-ui.command-dialog x-model="commandOpen">
        <x-ui.command-input placeholder="Type a command or search..." />
        <x-ui.command-list>
            <x-ui.command-empty>No results found.</x-ui.command-empty>
            <x-ui.command-group heading="Navigation">
                <x-ui.command-item>
                    <x-lucide-home />
                    <span>Home</span>
                    <x-ui.command-shortcut>⌘H</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-inbox />
                    <span>Inbox</span>
                    <x-ui.command-shortcut>⌘I</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-file-text />
                    <span>Documents</span>
                    <x-ui.command-shortcut>⌘D</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-folder />
                    <span>Folders</span>
                    <x-ui.command-shortcut>⌘F</x-ui.command-shortcut>
                </x-ui.command-item>
            </x-ui.command-group>
            <x-ui.command-separator />
            <x-ui.command-group heading="Actions">
                <x-ui.command-item>
                    <x-lucide-plus />
                    <span>New File</span>
                    <x-ui.command-shortcut>⌘N</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-folder-plus />
                    <span>New Folder</span>
                    <x-ui.command-shortcut>⇧⌘N</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-copy />
                    <span>Copy</span>
                    <x-ui.command-shortcut>⌘C</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-scissors />
                    <span>Cut</span>
                    <x-ui.command-shortcut>⌘X</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-clipboard-paste />
                    <span>Paste</span>
                    <x-ui.command-shortcut>⌘V</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-trash />
                    <span>Delete</span>
                    <x-ui.command-shortcut>⌫</x-ui.command-shortcut>
                </x-ui.command-item>
            </x-ui.command-group>
            <x-ui.command-separator />
            <x-ui.command-group heading="View">
                <x-ui.command-item>
                    <x-lucide-layout-grid />
                    <span>Grid View</span>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-list />
                    <span>List View</span>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-zoom-in />
                    <span>Zoom In</span>
                    <x-ui.command-shortcut>⌘+</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-zoom-out />
                    <span>Zoom Out</span>
                    <x-ui.command-shortcut>⌘-</x-ui.command-shortcut>
                </x-ui.command-item>
            </x-ui.command-group>
            <x-ui.command-separator />
            <x-ui.command-group heading="Account">
                <x-ui.command-item>
                    <x-lucide-user />
                    <span>Profile</span>
                    <x-ui.command-shortcut>⌘P</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-credit-card />
                    <span>Billing</span>
                    <x-ui.command-shortcut>⌘B</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-settings />
                    <span>Settings</span>
                    <x-ui.command-shortcut>⌘S</x-ui.command-shortcut>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-bell />
                    <span>Notifications</span>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-circle-help />
                    <span>Help & Support</span>
                </x-ui.command-item>
            </x-ui.command-group>
            <x-ui.command-separator />
            <x-ui.command-group heading="Tools">
                <x-ui.command-item>
                    <x-lucide-calculator />
                    <span>Calculator</span>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-calendar />
                    <span>Calendar</span>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-image />
                    <span>Image Editor</span>
                </x-ui.command-item>
                <x-ui.command-item>
                    <x-lucide-code />
                    <span>Code Editor</span>
                </x-ui.command-item>
            </x-ui.command-group>
        </x-ui.command-list>
    </x-ui.command-dialog>
</div>
