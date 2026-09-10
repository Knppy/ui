<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Knppy\Ui\Enums\ColorScheme;

beforeEach(function (): void {
    $resourceDirectories = [resource_path('css'), resource_path('js')];

    foreach ($resourceDirectories as $directory) {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
    }
});

afterEach(function (): void {
    $resourceDirectories = [resource_path('css'), resource_path('js')];

    foreach ($resourceDirectories as $directory) {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
    }
});

test('creates app.css when it does not exist', function (): void {
    $this->artisan('ui:install', ['--baseColor' => 'neutral'])
        ->assertSuccessful();

    expect(File::exists(resource_path('css/app.css')))->toBeTrue();
    expect(File::get(resource_path('css/app.css')))->toContain('--background');
});

test('outputs created message when app.css did not exist', function (): void {
    $this->artisan('ui:install', ['--baseColor' => 'neutral'])
        ->expectsOutputToContain('Created app.css')
        ->assertSuccessful();
});

test('updates existing app.css when --force flag is provided', function (): void {
    File::makeDirectory(resource_path('css'), 0755, true);
    File::put(resource_path('css/app.css'), 'old content');

    $this->artisan('ui:install', ['--baseColor' => 'neutral', '--force' => true])
        ->assertSuccessful();

    expect(File::get(resource_path('css/app.css')))->not->toBe('old content');
});

test('outputs updated message when app.css was overwritten with --force', function (): void {
    File::makeDirectory(resource_path('css'), 0755, true);
    File::put(resource_path('css/app.css'), 'old content');

    $this->artisan('ui:install', ['--baseColor' => 'neutral', '--force' => true])
        ->expectsOutputToContain('Updated app.css')
        ->assertSuccessful();
});

test('does not update existing app.css when user declines confirmation', function (): void {
    File::makeDirectory(resource_path('css'), 0755, true);
    File::put(resource_path('css/app.css'), 'old content');

    $this->artisan('ui:install', ['--baseColor' => 'neutral'])
        ->expectsConfirmation('Update app.css with neutral theme?', 'no')
        ->assertSuccessful();

    expect(File::get(resource_path('css/app.css')))->toBe('old content');
});

test('updates existing app.css when user confirms', function (): void {
    File::makeDirectory(resource_path('css'), 0755, true);
    File::put(resource_path('css/app.css'), 'old content');

    $this->artisan('ui:install', ['--baseColor' => 'neutral'])
        ->expectsConfirmation('Update app.css with neutral theme?', 'yes')
        ->assertSuccessful();

    expect(File::get(resource_path('css/app.css')))->not->toBe('old content');
});

test('uses interactive choice when --baseColor is not provided', function (): void {
    $this->artisan('ui:install')
        ->expectsChoice('Select a base color', 'neutral', ColorScheme::cases())
        ->assertSuccessful();

    expect(File::exists(resource_path('css/app.css')))->toBeTrue();
});

test('creates css directory if it does not exist', function (): void {
    expect(File::isDirectory(resource_path('css')))->toBeFalse();

    $this->artisan('ui:install', ['--baseColor' => 'neutral'])
        ->assertSuccessful();

    expect(File::isDirectory(resource_path('css')))->toBeTrue();
});

test('installs the Alpine component registrations', function (): void {
    $this->artisan('ui:install', ['--baseColor' => 'neutral'])
        ->assertSuccessful();

    expect(File::get(resource_path('js/ui.js')))
        ->toContain("Alpine.data('uiAccordion', accordion)")
        ->toContain("Alpine.data('uiTabs', tabs)")
        ->toContain("Alpine.data('uiSwitch', disclosure)");
});
