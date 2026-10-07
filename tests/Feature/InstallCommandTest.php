<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Knppy\Ui\ColorScheme;

beforeEach(function () {
    File::delete(base_path('components.json'));
    File::delete(resource_path('css/app.css'));
    File::ensureDirectoryExists(resource_path('css'));
});

afterEach(function () {
    File::delete(base_path('components.json'));
    File::delete(resource_path('css/app.css'));
});

it('installs files for the given base color', function () {
    $this->artisan('ui:install', ['--baseColor' => 'zinc'])->assertSuccessful();

    expect(json_decode(File::get(base_path('components.json')), true)['tailwind']['baseColor'])->toBe('zinc')
        ->and(File::get(resource_path('css/app.css')))
        ->toContain(ColorScheme::ZINC->getAppCssVariables())
        ->not->toContain('{{THEME_VARIABLES}}');
});

it('fails when already initialized without --force', function () {
    File::put(base_path('components.json'), 'existing');

    $this->artisan('ui:install', ['--baseColor' => 'zinc'])->assertFailed();

    expect(File::get(base_path('components.json')))->toBe('existing');
});

it('overwrites with --force', function () {
    File::put(base_path('components.json'), 'existing');

    $this->artisan('ui:install', ['--baseColor' => 'stone', '--force' => true])->assertSuccessful();

    expect(File::get(base_path('components.json')))->toContain('"baseColor": "stone"');
});

it('warns and prompts when the base color is unknown', function () {
    $this->artisan('ui:install', ['--baseColor' => 'bogus'])
        ->expectsOutputToContain('Base color is unavailable.')
        ->expectsChoice('Which base color would you like to use?', 'gray', ['neutral', 'zinc', 'stone', 'gray'])
        ->assertSuccessful();

    expect(File::get(base_path('components.json')))->toContain('"baseColor": "gray"');
});
