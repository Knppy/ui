@props(['open' => false])

<div
    x-data="uiDialog(@js($open))"
    x-modelable="open"
    x-on:keydown.escape.window="closeDialog()"
    data-slot="alert-dialog"
    {{ $attributes }}
>
    {{ $slot }}
</div>
