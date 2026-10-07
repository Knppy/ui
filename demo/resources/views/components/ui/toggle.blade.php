@props([
    'pressed' => false,
    'variant' => 'default',
    'size' => 'default',
])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('group/toggle inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium whitespace-nowrap transition-[color,box-shadow] outline-none hover:bg-muted hover:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 data-[state=on]:bg-accent data-[state=on]:text-accent-foreground dark:aria-invalid:ring-destructive/40 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4')
        ->add($variant === 'outline' ? 'border border-input bg-transparent shadow-xs hover:bg-accent hover:text-accent-foreground' : 'bg-transparent')
        ->add(match ($size) {
            'sm' => 'h-8 min-w-8 px-1.5',
            'lg' => 'h-10 min-w-10 px-2.5',
            default => 'h-9 min-w-9 px-2',
        })
        ->add($attributes['class'] ?? '');
@endphp

<button
    type="button"
    x-data="uiToggle(@js($pressed))"
    x-modelable="pressed"
    x-on:click="toggle"
    x-bind:aria-pressed="pressed"
    x-bind:data-state="pressed ? 'on' : 'off'"
    data-slot="toggle"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    {{ $attributes->twMerge(['class' => $classes]) }}
>
    {{ $slot }}
</button>
