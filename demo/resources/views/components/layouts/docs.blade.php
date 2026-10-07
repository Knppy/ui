@props([
    'title' => null,
    'description' => null,
    'active' => null,
])

<x-layouts.app
    :title="$title"
    :description="$description ?: 'shadcn/ui components for Laravel — Blade, Alpine & Tailwind v4. Browse, copy and own the code.'"
>
    <div class="min-h-screen">
        {{-- TODO: Topbar--}}
        {{-- Main content--}}
        <div class="mx-auto flex max-w-screen-2xl">
            {{-- Sidebar --}}
            <aside
                id="docs-sidebar"
                class="bg-background fixed inset-y-0 top-14 left-0 z-30 w-64 shrink-0 overflow-y-auto border-r p-4 transition-transform lg:sticky lg:top-14 lg:h-[calc(100svh-3.5rem)] lg:translate-x-0"
            >
                <nav class="space-y-6 text-sm"></nav>
            </aside>

            {{-- Content --}}
            <main class="min-w-0 flex-1 px-4 py-8 lg:px-10">{{ $slot }}</main>
        </div>
    </div>
</x-layouts.app>
