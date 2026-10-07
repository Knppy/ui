@props(['variant' => 'default'])

@php
    use Knppy\Ui\Facades\Ui;

    $classes = Ui::classes()
        ->add('group/tabs-list inline-flex w-fit items-center justify-center rounded-lg p-[3px] text-muted-foreground group-data-[orientation=horizontal]/tabs:h-9 group-data-[orientation=vertical]/tabs:h-fit group-data-[orientation=vertical]/tabs:flex-col data-[variant=line]:rounded-none')
        ->add($variant === 'line' ? 'gap-1 bg-transparent' : 'bg-muted')
        ->add($attributes['class'] ?? '');
@endphp

<div
    role="tablist"
    x-bind:aria-orientation="orientation"
    x-on:keydown.right="if (orientation === 'horizontal') move($event, 1);"
    x-on:keydown.left="if (orientation === 'horizontal') move($event, -1);"
    x-on:keydown.down="if (orientation === 'vertical') move($event, 1);"
    x-on:keydown.up="if (orientation === 'vertical') move($event, -1);"
    x-on:keydown.home="move($event, 'first')"
    x-on:keydown.end="move($event, 'last')"
    data-slot="tabs-list"
    data-variant="{{ $variant }}"
    {{ $attributes->twMerge(['class' => $classes]) }}
>
    {{ $slot }}
</div>
