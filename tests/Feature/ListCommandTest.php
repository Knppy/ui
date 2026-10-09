<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->project = sys_get_temp_dir().'/knppy-project-'.uniqid();
    File::ensureDirectoryExists($this->project);
    $this->app->setBasePath($this->project);
});

afterEach(function () {
    File::deleteDirectory($this->project);
});

function registryIndex(string ...$names): array
{
    return ['name' => 'test', 'items' => array_map(
        fn (string $name): array => ['name' => $name, 'type' => 'registry:ui', 'description' => "$name description"],
        $names,
    )];
}

it('lists the components of the default registry', function () {
    Http::fake(['ui.knppy.com/r/registry.json' => Http::response(registryIndex('button', 'card'))]);

    $this->artisan('ui:list')
        ->expectsOutputToContain('@knppy/button')
        ->expectsOutputToContain('card description')
        ->assertSuccessful();
});

it('lists a registry from components.json', function (string $argument) {
    writeComponentsJson(['registries' => ['@acme' => 'https://acme.test/r/{name}.json']]);
    Http::fake(['acme.test/r/registry.json' => Http::response(registryIndex('widget'))]);

    $this->artisan('ui:list', ['registry' => $argument])
        ->expectsOutputToContain('@acme/widget')
        ->assertSuccessful();
})->with(['@acme', 'acme']);

it('lists a local directory registry', function () {
    File::ensureDirectoryExists($this->project.'/registry');
    File::put($this->project.'/registry/registry.json', json_encode(registryIndex('local-item')));
    writeComponentsJson(['registries' => ['@local' => $this->project.'/registry']]);

    $this->artisan('ui:list', ['registry' => '@local'])
        ->expectsOutputToContain('@local/local-item')
        ->assertSuccessful();
});

it('works without a components.json', function () {
    Http::fake(['ui.knppy.com/r/registry.json' => Http::response(registryIndex('button'))]);

    $this->artisan('ui:list')->assertSuccessful();
});

it('falls back to the bundled registry and only says so when verbose', function () {
    Http::fake(['ui.knppy.com/*' => Http::response('', 500)]);

    $this->artisan('ui:list')
        ->expectsOutputToContain('@knppy/button')
        ->doesntExpectOutputToContain('bundled registry')
        ->assertSuccessful();

    $this->artisan('ui:list', ['-v' => true])
        ->expectsOutputToContain('listing the bundled registry')
        ->assertSuccessful();
});

it('reports when a registry is empty', function () {
    Http::fake(['ui.knppy.com/r/registry.json' => Http::response(registryIndex())]);

    $this->artisan('ui:list')->expectsOutputToContain('No components found')->assertSuccessful();
});

it('fails for unknown registries and invalid indexes', function (string $registry, mixed $response, string $message) {
    writeComponentsJson(['registries' => ['@bad' => 'https://bad.test/r/{name}.json']]);
    Http::fake(['bad.test/*' => Http::response($response)]);

    $this->artisan('ui:list', ['registry' => $registry])
        ->expectsOutputToContain($message)
        ->assertFailed();
})->with([
    'unknown registry' => ['@nope', [], 'nope'],
    'no items' => ['@bad', ['name' => 'x'], 'missing items'],
    'item without name' => ['@bad', ['items' => [['type' => 'registry:ui']]], 'missing name'],
]);
