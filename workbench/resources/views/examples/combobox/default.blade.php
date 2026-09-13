@php
    $frameworks = [
        'Next.js',
        'SvelteKit',
        'Nuxt.js',
        'Remix',
        'Astro',
    ];
@endphp

<x-ui.combobox>
    <x-ui.combobox-input placeholder="Select a framework" />
    <x-ui.combobox-content>
        <x-ui.combobox-empty>No items found.</x-ui.combobox-empty>
        <x-ui.combobox-list>
            @foreach ($frameworks as $framework)
                <x-ui.combobox-item value="{{ $framework }}">{{ $framework }}</x-ui.combobox-item>
            @endforeach
        </x-ui.combobox-list>
    </x-ui.combobox-content>
</x-ui.combobox>
