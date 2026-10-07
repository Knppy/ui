<x-ui.message-scroller class="h-72 max-w-md rounded-lg border">
    <x-ui.message-scroller-viewport class="p-4">
        <x-ui.message-scroller-content class="gap-4">
            @foreach (range(1, 100) as $message)
                <x-ui.message-scroller-item>
                    <x-ui.message>
                        <x-ui.message-content>
                            Message {{ $message }} in a scrollable conversation.
                        </x-ui.message-content>
                    </x-ui.message>
                </x-ui.message-scroller-item>
            @endforeach
        </x-ui.message-scroller-content>
    </x-ui.message-scroller-viewport>
    <x-ui.message-scroller-button />
</x-ui.message-scroller>
