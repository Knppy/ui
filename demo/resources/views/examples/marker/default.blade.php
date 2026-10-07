<div class="flex w-full max-w-sm flex-col gap-8 py-12">
    <x-ui.marker>
        <x-ui.marker-icon>
            <x-lucide-git-branch />
        </x-ui.marker-icon>
        <x-ui.marker-content>Switched to a new branch</x-ui.marker-content>
    </x-ui.marker>
    <x-ui.marker role="status">
        <x-ui.marker-icon>
            <x-ui.spinner />
        </x-ui.marker-icon>
        <x-ui.marker-content class="shimmer">Thinking...</x-ui.marker-content>
    </x-ui.marker>
    <x-ui.marker variant="separator">
        <x-ui.marker-content>Conversation compacted</x-ui.marker-content>
    </x-ui.marker>
    <x-ui.marker>
        <x-ui.marker-icon>
            <x-lucide-search />
        </x-ui.marker-icon>
        <x-ui.marker-content>Explored 4 files</x-ui.marker-content>
    </x-ui.marker>
</div>
