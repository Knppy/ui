@php
    use Illuminate\Support\Str;

    $dir = resource_path('views/examples/'.$slug);
    $examples = collect(glob($dir.'/*.blade.php'))
        ->map(fn ($p) => basename($p, '.blade.php'))
        ->sort(function ($a, $b) {
            if ($a === 'default') {
                return -1;
            }

            if ($b === 'default') {
                return 1;
            }

            return strcmp($a, $b);
        })
        ->values();

    $title = config('docs.labels.'.$slug) ?? Str::headline($slug);
    $description = config('docs.descriptions.'.$slug);
    $notes = config('docs.notes.'.$slug, []);
@endphp
<x-layouts.docs
    :active="$slug"
    :title="$title"
    :description="($description ?? null) ? $description.' A Laravel Blade component you copy, paste and own.' : $title.' component for Laravel Blade — copy, paste and own the code.'"
>
    @forelse ($examples as $ex)
        <section class="mt-10">
            @unless ($ex === 'default')
                <h2 class="mb-1 text-xl font-semibold tracking-tight">{{ Str::headline($ex) }}</h2>
            @endunless

            <x-preview :file="$slug.'.'.$ex" />
        </section>
    @empty
        <p class="text-muted-foreground mt-10">No examples yet.</p>
    @endforelse
</x-layouts.docs>
