<x-layouts.app>
    <div class="min-h-screen">
        <div class="mx-auto flex max-w-screen-2xl">
            {{-- Sidebar --}}
            <aside class="bg-background fixed inset-y-0 top-14 left-0 z-30 w-64 shrink-0 overflow-y-auto border-r p-4 transition-transform lg:sticky lg:top-14 lg:h-[calc(100svh-3.5rem)] lg:translate-x-0">
                TODO
            </aside>

            {{-- Main content --}}
            <main class="min-w-0 flex-1 px-4 py-8 lg:px-10">
                <div class="mx-auto max-w-3xl">{{ $slot }}</div>
            </main>
        </div>
    </div>
</x-layouts.app>
