@php
    $images = [
        [
            'name' => 'workspace.png',
            'meta' => 'PNG · 820 KB',
            'src' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=900&auto=format&fit=crop&q=80',
            'alt' => 'Workspace',
        ],
        [
            'name' => 'desk-reference.jpg',
            'meta' => 'JPG · 1.1 MB',
            'src' => 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?w=900&auto=format&fit=crop&q=80',
            'alt' => 'Desk',
        ],
        [
            'name' => 'office-reference.jpg',
            'meta' => 'JPG · 940 KB',
            'src' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=900&auto=format&fit=crop&q=80',
            'alt' => 'Office',
        ],
    ];
@endphp

<div class="mx-auto flex w-full max-w-sm flex-col gap-3 py-12">
    <x-ui.attachment-group>
        @foreach ($images as $image)
            <x-ui.attachment key="{{ $image['name'] }}" orientation="vertical">
                <x-ui.attachment-media variant="image">
                    <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}" />
                </x-ui.attachment-media>
                <x-ui.attachment-content>
                    <x-ui.attachment-title>{{ $image['name'] }}</x-ui.attachment-title>
                    <x-ui.attachment-description>{{ $image['meta'] }}</x-ui.attachment-description>
                </x-ui.attachment-content>
            </x-ui.attachment>
        @endforeach
    </x-ui.attachment-group>
    <x-ui.attachment state="uploading" class="w-full">
        <x-ui.attachment-media>
            <x-ui.spinner />
        </x-ui.attachment-media>
        <x-ui.attachment-content>
            <x-ui.attachment-title>sales-dashboard.pdf</x-ui.attachment-title>
            <x-ui.attachment-description>Uploading · 64%</x-ui.attachment-description>
        </x-ui.attachment-content>
        <x-ui.attachment-actions>
            <x-ui.attachment-action aria-label="Cancel upload">
                <x-lucide-x />
            </x-ui.attachment-action>
        </x-ui.attachment-actions>
    </x-ui.attachment>
    <x-ui.attachment class="w-full">
        <x-ui.attachment-media>
            <x-lucide-file-code />
        </x-ui.attachment-media>
        <x-ui.attachment-content>
            <x-ui.attachment-title>message-renderer.tsx</x-ui.attachment-title>
            <x-ui.attachment-description>TypeScript · 12 KB</x-ui.attachment-description>
        </x-ui.attachment-content>
        <x-ui.attachment-actions>
            <x-ui.attachment-action aria-label="Remove message-renderer.tsx">
                <x-lucide-x />
            </x-ui.attachment-action>
        </x-ui.attachment-actions>
    </x-ui.attachment>
</div>
