<div>
    <x-ui.button
        variant="outline"
        x-on:click="
            $toast('Event created', { description: 'Sunday, December 3 at 9:00 AM', action: { label: 'Undo' } })
        "
    >
        Show toast
    </x-ui.button>

    <x-ui.sonner closeButton />
</div>
