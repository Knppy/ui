@props([
    'title' => null,
    'description' => null,
    'active' => null,
])

@php
    use Knppy\Ui\Facades\Ui;

    $dir = resource_path('views/examples/*');

    $components = collect(glob($dir))
        ->map(fn ($p) => basename($p))
        ->all();

    $available = collect(glob($dir, GLOB_ONLYDIR))
        ->map(fn ($p) => basename($p))
        ->all();
@endphp

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
                class="bg-background fixed inset-y-0 top-14 left-0 z-30 w-64 shrink-0 overflow-y-auto border-r p-4 transition-transform lg:sticky lg:h-screen"
            >
                <nav class="space-y-6 text-sm">
                    <div>
                        <p class="text-muted-foreground mb-1.5 px-2 text-xs font-semibold tracking-wide uppercase">
                            Components
                        </p>
                        <ul class="space-y-0.5">
                            @foreach ($components as $componentSlug)
                                @php
                                    $has = in_array($componentSlug, $available);
                                    $title = config('docs.labels.'.$componentSlug) ?? Str::headline($componentSlug);

                                    $menuClasses = Ui::classes()
                                        ->add('flex items-center justify-between rounded-md px-2 py-1.5 transition-colors')
                                        ->add($active === $componentSlug ? 'bg-accent text-accent-foreground font-medium': '')
                                        ->add($active !== $componentSlug && $has ? 'hover:bg-accent/60 text-foreground' : '')
                                        ->add(!$has ? 'text-muted-foreground/50 cursor-not-allowed' : '');
                                @endphp
                                <li>
                                    <a
                                        href="{{ $has ? route('docs.component', $componentSlug) : '#' }}"
                                        class="{{ $menuClasses }}"
                                    >
                                        {{ $title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </nav>
            </aside>

            {{-- Content --}}
            <main class="min-w-0 flex-1 px-4 py-8 lg:px-10">{{ $slot }}</main>
        </div>
    </div>
</x-layouts.app>
