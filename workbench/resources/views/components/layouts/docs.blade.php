@php
    $dir = resource_path('views/examples/*');

    $components = collect(glob($dir))
        ->map(fn ($p) => basename($p))
        ->all();

    $available = collect(glob($dir, GLOB_ONLYDIR))
        ->map(fn ($p) => basename($p))
        ->all();
@endphp

<x-layouts.app>
    <x-ui.sidebar-provider>
        <x-ui.sidebar>
            <x-ui.sidebar-content>
                <x-ui.sidebar-group>
                    <x-ui.sidebar-group-label>Components</x-ui.sidebar-group-label>
                    <x-ui.sidebar-menu>
                        @foreach ($components as $componentSlug)
                            @php
                                $has = in_array($componentSlug, $available);
                                $title = config('docs.labels.'.$componentSlug) ?? Str::headline($componentSlug);
                            @endphp
                            <x-ui.sidebar-menu-item>
                                <x-ui.sidebar-menu-button
                                    is="a"
                                    href="{{ $has ? route('docs.component', $componentSlug) : '#' }}"
                                >
                                    {{ $title }}</x-ui.sidebar-menu-button>
                            </x-ui.sidebar-menu-item>
                        @endforeach
                    </x-ui.sidebar-menu>
                </x-ui.sidebar-group>
            </x-ui.sidebar-content>

            <x-ui.sidebar-rail/>
        </x-ui.sidebar>

        <x-ui.sidebar-inset>
            <header class="flex h-16 shrink-0 items-center gap-2 px-4">
                <x-ui.sidebar-trigger/>
            </header>

            <div class="mx-auto max-w-3xl px-4">
                {{ $slot }}
            </div>
        </x-ui.sidebar-inset>
    </x-ui.sidebar-provider>
</x-layouts.app>
