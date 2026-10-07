<img
    x-init="imageLoaded = $el.complete && $el.naturalWidth > 0"
    x-on:load="imageLoaded = true"
    x-on:error="imageLoaded = false"
    x-show="imageLoaded"
    data-slot="avatar-image"
    {{ $attributes->twMerge(['class' => 'aspect-square size-full']) }}
/>
