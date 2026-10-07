<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

beforeEach(function () {
    $this->project = sys_get_temp_dir().'/knppy-project-'.uniqid();
    File::ensureDirectoryExists($this->project);
    $this->app->setBasePath($this->project);
});

afterEach(function () {
    File::deleteDirectory($this->project);
});

it('installs a component into the ui alias', function () {
    writeComponentsJson();
    Http::fake(['ui.knppy.com/*' => Http::response(registryPayload('button'))]);

    $this->artisan('ui:add', ['components' => ['button']])
        ->expectsOutputToContain('resources/views/components/ui/button.blade.php created.')
        ->assertSuccessful();

    expect(File::get(base_path('resources/views/components/ui/button.blade.php')))->toBe('button content');
});

it('honors custom aliases and installs registry dependencies and multiple components', function () {
    writeComponentsJson(['aliases' => ['ui' => 'resources/views/custom']]);
    Http::fake([
        'ui.knppy.com/r/card.json' => Http::response(registryPayload('card', ['registryDependencies' => ['label']])),
        'ui.knppy.com/r/label.json' => Http::response(registryPayload('label')),
        'ui.knppy.com/r/button.json' => Http::response(registryPayload('button')),
    ]);

    $this->artisan('ui:add', ['components' => ['card', 'button']])->assertSuccessful();

    expect(File::exists(base_path('resources/views/custom/card.blade.php')))->toBeTrue()
        ->and(File::exists(base_path('resources/views/custom/label.blade.php')))->toBeTrue()
        ->and(File::exists(base_path('resources/views/custom/button.blade.php')))->toBeTrue();
});

it('skips existing files unless --overwrite is given', function () {
    writeComponentsJson();
    Http::fake(['ui.knppy.com/*' => Http::response(registryPayload('button'))]);
    File::ensureDirectoryExists(base_path('resources/views/components/ui'));
    File::put(base_path('resources/views/components/ui/button.blade.php'), 'custom');

    $this->artisan('ui:add', ['components' => ['button']])
        ->expectsOutputToContain('already exists, skipped')
        ->assertSuccessful();

    expect(File::get(base_path('resources/views/components/ui/button.blade.php')))->toBe('custom');

    $this->artisan('ui:add', ['components' => ['button'], '--overwrite' => true])->assertSuccessful();

    expect(File::get(base_path('resources/views/components/ui/button.blade.php')))->toBe('button content');
});

it('prints composer and npm commands with --skip-packages', function () {
    writeComponentsJson();
    Http::fake(['*' => Http::response(registryPayload('button', ['dependencies' => ['composer' => ['acme/pkg'], 'npm' => ['alpinejs']]]))]);

    Process::fake();

    $this->artisan('ui:add', ['components' => ['button'], '--skip-packages' => true])
        ->expectsOutputToContain('composer require acme/pkg')
        ->expectsOutputToContain('npm install alpinejs')
        ->assertSuccessful();

    Process::assertNothingRan();
});

it('installs missing composer and npm packages automatically', function () {
    writeComponentsJson();
    File::put(base_path('composer.json'), json_encode(['require' => ['acme/present' => '^1.0']]));
    File::put(base_path('package.json'), json_encode(['devDependencies' => ['@scope/present' => '^1.0']]));
    Http::fake(['*' => Http::response(registryPayload('button', ['dependencies' => [
        'composer' => ['acme/present', 'acme/pkg:^2.0'],
        'npm' => ['@scope/present@^1', 'alpinejs@^3'],
    ]]))]);
    Process::fake();

    $this->artisan('ui:add', ['components' => ['button']])->assertSuccessful();

    Process::assertRan(fn ($process) => $process->command === ['composer', 'require', 'acme/pkg:^2.0']);
    Process::assertRan(fn ($process) => $process->command === ['npm', 'install', 'alpinejs@^3']);
    Process::assertRanTimes(fn ($process) => true, 2);
});

it('does not install packages that already exist', function () {
    writeComponentsJson();
    File::put(base_path('composer.json'), json_encode(['require-dev' => ['acme/pkg' => '^1.0']]));
    Http::fake(['*' => Http::response(registryPayload('button', ['dependencies' => ['composer' => ['acme/pkg']]]))]);
    Process::fake();

    $this->artisan('ui:add', ['components' => ['button']])->assertSuccessful();

    Process::assertNothingRan();
});

it('prints the npm command instead of running it without a package.json', function () {
    writeComponentsJson();
    Http::fake(['*' => Http::response(registryPayload('button', ['dependencies' => ['npm' => ['alpinejs']]]))]);
    Process::fake();

    $this->artisan('ui:add', ['components' => ['button']])
        ->expectsOutputToContain('npm install alpinejs')
        ->assertSuccessful();

    Process::assertNothingRan();
});

it('fails and prints the manual command when a package install fails', function () {
    writeComponentsJson();
    Http::fake(['*' => Http::response(registryPayload('button', ['dependencies' => ['composer' => ['acme/pkg']]]))]);
    Process::fake(['*' => Process::result(exitCode: 1)]);

    $this->artisan('ui:add', ['components' => ['button']])
        ->expectsOutputToContain('composer require acme/pkg')
        ->assertFailed();

    expect(File::exists(base_path('resources/views/components/ui/button.blade.php')))->toBeTrue();
});

it('stays quiet about the bundled registry fallback by default', function () {
    writeComponentsJson();
    Http::fake(['*' => Http::response('', 404)]);

    $this->artisan('ui:add', ['components' => ['button']])
        ->doesntExpectOutputToContain('Remote registry unavailable')
        ->assertSuccessful();

    expect(File::exists(base_path('resources/views/components/ui/button.blade.php')))->toBeTrue();
});

it('warns about the bundled registry fallback in verbose mode', function () {
    writeComponentsJson();
    Http::fake(['*' => Http::response('', 404)]);

    $this->artisan('ui:add', ['components' => ['button'], '-v' => true])
        ->expectsOutputToContain('Remote registry unavailable, used bundled registry for button.')
        ->assertSuccessful();
});

it('uses registries from components.json', function () {
    writeComponentsJson(['registries' => ['@acme' => 'https://acme.test/r/{name}.json']]);
    Http::fake(['acme.test/*' => Http::response(registryPayload('card'))]);

    $this->artisan('ui:add', ['components' => ['@acme/card']])->assertSuccessful();

    expect(File::exists(base_path('resources/views/components/ui/card.blade.php')))->toBeTrue();
});

it('fails without components.json', function () {
    $this->artisan('ui:add', ['components' => ['button']])
        ->expectsOutputToContain('Run ui:install first')
        ->assertFailed();
});

it('fails for unknown components and registries', function (string $component) {
    writeComponentsJson();
    Http::fake(['*' => Http::response('', 404)]);

    $this->artisan('ui:add', ['components' => [$component]])->assertFailed();
})->with(['nope', '@nope/button']);

it('installs javascript files into the js alias and hints at the import', function () {
    writeComponentsJson();
    Http::fake(['ui.knppy.com/*' => Http::response(registryPayload('alpine', [
        'type' => 'registry:js',
        'files' => [
            ['path' => 'ui.js', 'type' => 'registry:js', 'content' => 'loader'],
            ['path' => 'ui/lib/id.js', 'type' => 'registry:js', 'content' => 'id'],
        ],
    ]))]);

    $this->artisan('ui:add', ['components' => ['alpine'], '--skip-packages' => true])
        ->expectsOutputToContain("import './ui.js';")
        ->assertSuccessful();

    expect(File::get(base_path('resources/js/ui.js')))->toBe('loader')
        ->and(File::exists(base_path('resources/js/ui/lib/id.js')))->toBeTrue();
});

it('fails clearly when the js alias is missing', function () {
    writeComponentsJson(['aliases' => ['ui' => 'resources/views/components/ui']]);
    Http::fake(['ui.knppy.com/*' => Http::response(registryPayload('alpine', [
        'files' => [['path' => 'ui.js', 'type' => 'registry:js', 'content' => 'loader']],
    ]))]);

    $this->artisan('ui:add', ['components' => ['alpine']])->assertFailed();
});
