<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

test('components render normally with livewire/blaze installed and enabled by default', function (): void {
    $html = Blade::render('<x-ui.badge>New</x-ui.badge>');

    expect($html)->toContain('New');
});

test('field-error renders normally with livewire/blaze installed, without opting into folding', function (): void {
    $html = Blade::render('<x-ui.field-error :errors="[\'Required.\']"></x-ui.field-error>');

    expect($html)
        ->toContain('Required.')
        ->toContain('data-slot="field-error"');
});
