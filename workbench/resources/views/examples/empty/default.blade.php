<x-ui.empty>
    <x-ui.empty-header>
        <x-ui.empty-media variant="icon">
            <x-lucide-folder-code />
        </x-ui.empty-media>
        <x-ui.empty-title>No Projects Yet</x-ui.empty-title>
        <x-ui.empty-description>
            You haven't created any projects yet. Get started by creating your first project.
        </x-ui.empty-description>
    </x-ui.empty-header>
    <x-ui.empty-content class="flex-row justify-center gap-2">
        <x-ui.button>Create Project</x-ui.button>
        <x-ui.button variant="outline">Import Project</x-ui.button>
    </x-ui.empty-content>
    <x-ui.button is="a" variant="link" class="text-muted-foreground" size="sm">
        Learn More <x-lucide-arrow-up-right />
    </x-ui.button>
</x-ui.empty>
