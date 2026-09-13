<x-ui.resizable-panel-group orientation="horizontal" class="max-w-sm rounded-lg border">
    <x-ui.resizable-panel defaultSize="50">
        <div class="flex h-[200px] items-center justify-center p-6">
            <span class="font-semibold">One</span>
        </div>
    </x-ui.resizable-panel>
    <x-ui.resizable-handle withHandle />
    <x-ui.resizable-panel defaultSize="50">
        <x-ui.resizable-panel-group orientation="vertical">
            <x-ui.resizable-panel defaultSize="25">
                <div class="flex h-full items-center justify-center p-6">
                    <span class="font-semibold">Two</span>
                </div>
            </x-ui.resizable-panel>
            <x-ui.resizable-handle withHandle />
            <x-ui.resizable-panel defaultSize="75">
                <div class="flex h-full items-center justify-center p-6">
                    <span class="font-semibold">Three</span>
                </div>
            </x-ui.resizable-panel>
        </x-ui.resizable-panel-group>
    </x-ui.resizable-panel>
</x-ui.resizable-panel-group>
