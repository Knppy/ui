@props(['variant' => 'outline', 'size' => 'default'])

<x-ui.button
    dataSlot="alert-dialog-cancel"
    :variant="$variant"
    :size="$size"
    x-on:click="closeDialog()"
    {{ $attributes }}
>
    {{ $slot }}</x-ui.button>
