<span
    x-init="registerTrigger($el.firstElementChild)"
    x-effect="trigger?.setAttribute('aria-describedby', open ? contentId : '')"
    x-on:mouseover="show()"
    x-on:mouseout="if (! $el.contains($event.relatedTarget)) hide();"
    x-on:focusin="show()"
    x-on:focusout="if (! $el.contains($event.relatedTarget)) hide();"
    data-slot="tooltip-trigger"
    {{ $attributes->twMerge(['class' => 'contents']) }}
>
    {{ $slot }}
</span>
