@props(['variant' => 'default', 'size' => 'default'])

<x-ui.button
    dataSlot="alert-dialog-action"
    :variant="$variant"
    :size="$size"
    x-on:click="closeDialog()"
    {{ $attributes }}
>
    {{ $slot }}</x-ui.button>
