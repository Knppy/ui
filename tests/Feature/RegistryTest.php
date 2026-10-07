<?php

declare(strict_types=1);

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Knppy\Ui\Registry\Registry;
use Knppy\Ui\Registry\RegistryException;
use Knppy\Ui\Registry\RegistrySource;

it('resolves a bare name against the default registry', function () {
    Http::fake(['ui.knppy.com/r/button.json' => Http::response(registryPayload('button'))]);

    $item = app(Registry::class)->resolve('button');

    expect($item->name)->toBe('button')
        ->and($item->source)->toBe(RegistrySource::Remote)
        ->and($item->files[0]->content)->toBe('button content');
});

it('routes @namespace/name to the matching registry', function () {
    Http::fake(['acme.test/r/card.json' => Http::response(registryPayload('card'))]);

    $registry = new Registry(['@knppy' => 'https://ui.knppy.com/r/{name}.json', '@acme' => 'https://acme.test/r/{name}.json']);

    expect($registry->resolve('@acme/card')->name)->toBe('card');
});

it('throws for unknown namespaces and invalid references', function (string $reference) {
    app(Registry::class)->resolve($reference);
})->with(['@nope/button', '../etc/passwd', 'a/b'])->throws(RegistryException::class);

it('lets components.json registries override the configured ones', function () {
    Http::fake(['override.test/*' => Http::response(registryPayload('button'))]);

    $registry = app(Registry::class)->withRegistries(['@knppy' => 'https://override.test/{name}.json']);

    expect($registry->resolve('button')->name)->toBe('button');
    Http::assertSent(fn ($request) => $request->url() === 'https://override.test/button.json');
});

it('resolves a local directory registry', function () {
    $directory = sys_get_temp_dir().'/knppy-registry-'.uniqid();
    File::ensureDirectoryExists($directory);
    File::put("{$directory}/button.json", json_encode(registryPayload('button'), JSON_THROW_ON_ERROR));

    $item = (new Registry(['@knppy' => $directory]))->resolve('button');
    File::deleteDirectory($directory);

    expect($item->source)->toBe(RegistrySource::Local);
});

it('resolves dependencies first, de-duplicated and cycle-safe', function () {
    Http::fake([
        'ui.knppy.com/r/card.json' => Http::response(registryPayload('card', ['registryDependencies' => ['button', 'label']])),
        'ui.knppy.com/r/button.json' => Http::response(registryPayload('button', ['registryDependencies' => ['label', 'card']])),
        'ui.knppy.com/r/label.json' => Http::response(registryPayload('label')),
    ]);

    $names = array_map(fn ($item) => $item->name, app(Registry::class)->resolveWithDependencies('card'));

    expect($names)->toBe(['label', 'button', 'card']);
});

it('falls back to the bundled registry when the remote @knppy registry fails', function (Closure $fake) {
    Http::fake($fake);

    $item = app(Registry::class)->resolve('button');

    expect($item->source)->toBe(RegistrySource::Fallback)
        ->and($item->files[0]->path)->toBe('button.blade.php');
})->with([
    '404' => fn () => ['*' => Http::response('', 404)],
    '500' => fn () => ['*' => Http::response('', 500)],
    'invalid json' => fn () => ['*' => Http::response('not json')],
    'invalid item' => fn () => ['*' => Http::response(['name' => 'button'])],
    'connection error' => fn () => ['*' => fn () => throw new ConnectionException('offline')],
]);

it('does not fall back for other registries', function () {
    Http::fake(['*' => Http::response('', 404)]);

    $registry = new Registry(
        ['@acme' => 'https://acme.test/r/{name}.json'],
        '@acme',
        ['@knppy' => __DIR__.'/../../registry/r'],
    );

    $registry->resolve('button');
})->throws(RegistryException::class);

it('does not fall back when disabled', function () {
    config(['ui.fallback' => false]);
    Http::fake(['*' => Http::response('', 404)]);

    app(Registry::class)->resolve('button');
})->throws(RegistryException::class);

it('rethrows the remote error when the bundled registry lacks the item', function () {
    Http::fake(['*' => Http::response('', 404)]);

    app(Registry::class)->resolve('does-not-exist');
})->throws(RegistryException::class, 'ui.knppy.com');

it('rejects registry files that escape the alias directory', function (string $path) {
    Http::fake(['*' => Http::response(registryPayload('button', ['files' => [['path' => $path, 'content' => 'x']]]))]);
    config(['ui.fallback' => false]);

    app(Registry::class)->resolve('button');
})->with(['../evil.php', '/etc/passwd', 'a/../../evil.php'])->throws(RegistryException::class);
