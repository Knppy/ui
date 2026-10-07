@php
    $items = [
        ['label' => 'Select a fruit', 'value' => null],
        ['label' => 'Apple', 'value' => 'apple'],
        ['label' => 'Banana', 'value' => 'banana'],
        ['label' => 'Blueberry', 'value' => 'blueberry'],
        ['label' => 'Grapes', 'value' => 'grapes'],
        ['label' => 'Pineapple', 'value' => 'pineapple'],
    ];
@endphp

<x-ui.select>
    <x-ui.select-trigger class="w-full max-w-48">
        <x-ui.select-value />
    </x-ui.select-trigger>
    <x-ui.select-content>
        <x-ui.select-group>
            <x-ui.select-label>Fruits</x-ui.select-label>
            @foreach ($items as $item)
                <x-ui.select-item value="{{ $item['value'] }}"> {{ $item['label'] }} </x-ui.select-item>
            @endforeach
        </x-ui.select-group>
    </x-ui.select-content>
</x-ui.select>
