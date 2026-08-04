<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Knppy\Ui\Enums\ColorScheme;

// File-system state at base_path() is shared across parallel workers.
uses()->group('serial');

beforeEach(function () {
    // Ensure the css directory exists in the testbench app
    File::ensureDirectoryExists(resource_path('css'));

    // Ensure components.json does not exist before each test
    File::delete(base_path('components.json'));
    File::delete(resource_path('css/app.css'));
    File::delete(resource_path('css/app.js'));
    File::delete(base_path('package.json'));
});

afterEach(function () {
    File::delete(base_path('components.json'));
    File::delete(resource_path('css/app.css'));
    File::delete(resource_path('css/app.js'));
    File::delete(base_path('package.json'));
});

it('fails when components.json already exists and --force is not passed', function () {
    File::put(base_path('components.json'), '{}');

    $this->artisan('ui:install')
        ->expectsOutputToContain('Project already initialized. Use --force to reinitialize.')
        ->assertFailed();
});

it('succeeds on a fresh install', function () {
    $this->artisan('ui:install')
        ->expectsChoice('Which base color would you like to use?', ColorScheme::NEUTRAL->value, [ColorScheme::NEUTRAL->value])
        ->assertSuccessful();
});

it('creates components.json replacing the base color placeholder', function () {
    $this->artisan('ui:install')
        ->expectsChoice('Which base color would you like to use?', ColorScheme::NEUTRAL->value, [ColorScheme::NEUTRAL->value])
        ->assertSuccessful();

    expect(base_path('components.json'))->toBeFile();

    $contents = File::get(base_path('components.json'));
    expect($contents)->not->toContain('{{BASE_COLOR}}');
    expect($contents)->toContain(ColorScheme::NEUTRAL->value);
});

it('creates app.css with the base color theme injected', function () {
    $this->artisan('ui:install')
        ->expectsChoice('Which base color would you like to use?', ColorScheme::NEUTRAL->value, [ColorScheme::NEUTRAL->value])
        ->assertSuccessful();

    expect(resource_path('css/app.css'))->toBeFile();

    $contents = File::get(resource_path('css/app.css'));
    expect($contents)->not->toContain('{{THEME_COLOR}}');
    expect($contents)->toContain(ColorScheme::NEUTRAL->getCss());
});

it('creates app.js', function () {
    $this->artisan('ui:install')
        ->expectsChoice('Which base color would you like to use?', ColorScheme::NEUTRAL->value, [ColorScheme::NEUTRAL->value])
        ->assertSuccessful();

    expect(resource_path('css/app.js'))->toBeFile();
});

it('displays the success message and next steps', function () {
    $this->artisan('ui:install')
        ->expectsChoice('Which base color would you like to use?', ColorScheme::NEUTRAL->value, [ColorScheme::NEUTRAL->value])
        ->expectsOutputToContain("UI's resources and configurations installed successfully.")
        ->expectsOutputToContain('Next steps:')
        ->expectsOutputToContain('php artisan ui:add button')
        ->expectsOutputToContain('<x-ui.button>Click me</x-ui.button>')
        ->assertSuccessful();
});

it('reinitializes when --force is passed and components.json already exists', function () {
    File::put(base_path('components.json'), '{"existing": true}');

    $this->artisan('ui:install --force')
        ->expectsChoice('Which base color would you like to use?', ColorScheme::NEUTRAL->value, [ColorScheme::NEUTRAL->value])
        ->assertSuccessful();

    $contents = File::get(base_path('components.json'));
    expect($contents)->not->toContain('"existing"');
});

it('updates package.json dependencies when it exists', function () {
    File::put(base_path('package.json'), json_encode([
        'dependencies' => ['lodash' => '^4.0.0'],
    ]));

    $this->artisan('ui:install')
        ->expectsChoice('Which base color would you like to use?', ColorScheme::NEUTRAL->value, [ColorScheme::NEUTRAL->value])
        ->assertSuccessful();

    $packageJson = json_decode(File::get(base_path('package.json')), true);

    expect($packageJson['dependencies'])->toHaveKey('alpinejs');
    expect($packageJson['dependencies'])->toHaveKey('@alpinejs/anchor');
    expect($packageJson['dependencies'])->toHaveKey('@alpinejs/collapse');
    expect($packageJson['dependencies'])->toHaveKey('@alpinejs/focus');
    expect($packageJson['dependencies'])->toHaveKey('lodash');
});

it('does not modify package.json when it does not exist', function () {
    $this->artisan('ui:install')
        ->expectsChoice('Which base color would you like to use?', ColorScheme::NEUTRAL->value, [ColorScheme::NEUTRAL->value])
        ->assertSuccessful();

    expect(base_path('package.json'))->not->toBeFile();
});

it('adds dependencies when package.json has no existing dependencies key', function () {
    File::put(base_path('package.json'), json_encode(['name' => 'test-app']));

    $this->artisan('ui:install')
        ->expectsChoice('Which base color would you like to use?', ColorScheme::NEUTRAL->value, [ColorScheme::NEUTRAL->value])
        ->assertSuccessful();

    $packageJson = json_decode(File::get(base_path('package.json')), true);

    expect($packageJson['dependencies'])->toHaveKey('alpinejs');
});
