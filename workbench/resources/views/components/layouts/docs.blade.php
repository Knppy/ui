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
    <div class="min-h-screen">
        <div class="mx-auto flex max-w-screen-2xl">
            {{-- Sidebar --}}
            <aside class="bg-background fixed inset-y-0 top-14 left-0 z-30 w-64 shrink-0 overflow-y-auto border-r p-4 transition-transform lg:sticky lg:top-14 lg:h-[calc(100svh-3.5rem)] lg:translate-x-0">
                <ul>
                    @foreach ($components as $component)
                        @php $has = in_array($component, $available); @endphp
                        <li>
                            <a href="{{ $has ? route('docs.component', $component) : '#' }}">{{ $component }}</a>
                        </li>
                    @endforeach
                </ul>
            </aside>

            {{-- Main content --}}
            <main class="min-w-0 flex-1 px-4 py-8 lg:px-10">
                <div class="mx-auto max-w-3xl">{{ $slot }}</div>
            </main>
        </div>
    </div>
</x-layouts.app>
