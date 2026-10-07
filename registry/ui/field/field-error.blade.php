@props(['errors' => []])

@php
    $messages = collect($errors)
        ->map(function ($error) {
            if (is_string($error)) {
                return $error;
            }

            if (is_array($error)) {
                return $error['message'] ?? null;
            }

            return is_object($error) ? ($error->message ?? null) : null;
        })
        ->filter()
        ->unique()
        ->values();
    $hasContent = $slot->isNotEmpty() || $messages->isNotEmpty();
@endphp

@if ($hasContent)
    <div
        role="alert"
        data-slot="field-error"
        {{ $attributes->twMerge(['class' => 'text-sm font-normal text-destructive']) }}
    >
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @elseif ($messages->count() === 1)
            {{ $messages->first() }}
        @else
            <ul class="ml-4 flex list-disc flex-col gap-1">
                @foreach ($messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
