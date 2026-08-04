<?php

declare(strict_types=1);

use Knppy\Ui\Registry;

// ── Constructor ──────────────────────────────────────────────────────────────

it('loads a valid registry file', function (): void {
    $registry = new Registry;

    expect($registry->items())->toBeArray();
});

it('throws when registry file does not exist', function (): void {
    new Registry(registryPath: '/non/existent/registry.json');
})->throws(RuntimeException::class, 'Registry file not found');

it('throws when registry file contains invalid json', function (): void {
    $path = tempnam(sys_get_temp_dir(), 'registry_').'.json';
    file_put_contents($path, 'not-json');

    new Registry(registryPath: $path);
})->throws(RuntimeException::class, 'Registry file is not a valid JSON array');

it('throws when registry file contains a json scalar', function (): void {
    $path = tempnam(sys_get_temp_dir(), 'registry_').'.json';
    file_put_contents($path, '"just a string"');

    new Registry(registryPath: $path);
})->throws(RuntimeException::class, 'Registry file is not a valid JSON array');

it('uses the provided srcPath', function (): void {
    $registry = new Registry(srcPath: '/custom/src');

    expect($registry->srcPath())->toBe('/custom/src');
});

it('defaults srcPath to the registry directory', function (): void {
    $registry = new Registry;

    expect($registry->srcPath())->toBe(dirname(__DIR__, 2).'/registry');
});

// ── items() ──────────────────────────────────────────────────────────────────

it('returns items from the registry', function (): void {
    $registry = registryWithFixture([
        'items' => [
            ['name' => 'button'],
        ],
    ]);

    expect($registry->items())->toBe([['name' => 'button']]);
});

it('returns an empty array when items key is missing', function (): void {
    $registry = registryWithFixture(['name' => 'knppy']);

    expect($registry->items())->toBe([]);
});

// ── component() ──────────────────────────────────────────────────────────────

it('returns a component by name', function (): void {
    $registry = registryWithFixture([
        'items' => [
            ['name' => 'button', 'title' => 'Button'],
            ['name' => 'badge', 'title' => 'Badge'],
        ],
    ]);

    expect($registry->component('button'))->toBe(['name' => 'button', 'title' => 'Button']);
});

it('returns null for an unknown component', function (): void {
    $registry = registryWithFixture(['items' => []]);

    expect($registry->component('unknown'))->toBeNull();
});

// ── componentExists() ─────────────────────────────────────────────────────────

it('returns true when the component exists', function (): void {
    $registry = registryWithFixture([
        'items' => [['name' => 'button']],
    ]);

    expect($registry->componentExists('button'))->toBeTrue();
});

it('returns false when the component does not exist', function (): void {
    $registry = registryWithFixture([
        'items' => [['name' => 'button']],
    ]);

    expect($registry->componentExists('badge'))->toBeFalse();
});

// ── dependenciesFor() ─────────────────────────────────────────────────────────

it('returns dependencies for a component', function (): void {
    $dependencies = ['composer' => ['vendor/package'], 'npm' => ['some-lib']];

    $registry = registryWithFixture([
        'items' => [
            ['name' => 'button', 'dependencies' => $dependencies],
        ],
    ]);

    expect($registry->dependenciesFor('button'))->toBe($dependencies);
});

it('returns an empty array when the component has no dependencies key', function (): void {
    $registry = registryWithFixture([
        'items' => [['name' => 'button']],
    ]);

    expect($registry->dependenciesFor('button'))->toBe([]);
});

it('returns an empty array for dependencies when the component does not exist', function (): void {
    $registry = registryWithFixture(['items' => []]);

    expect($registry->dependenciesFor('unknown'))->toBe([]);
});

// ── filesFor() ───────────────────────────────────────────────────────────────

it('returns files for a component', function (): void {
    $registry = registryWithFixture([
        'items' => [
            ['name' => 'button', 'files' => ['registry/ui/button/button.blade.php']],
        ],
    ]);

    expect($registry->filesFor('button'))->toBe(['registry/ui/button/button.blade.php']);
});

it('returns an empty array when the component has no files key', function (): void {
    $registry = registryWithFixture([
        'items' => [['name' => 'button']],
    ]);

    expect($registry->filesFor('button'))->toBe([]);
});

it('returns an empty array for files when the component does not exist', function (): void {
    $registry = registryWithFixture(['items' => []]);

    expect($registry->filesFor('unknown'))->toBe([]);
});

// ── registryDependenciesFor() ─────────────────────────────────────────────────

it('returns registry dependencies for a component', function (): void {
    $registry = registryWithFixture([
        'items' => [
            ['name' => 'badge', 'registryDependencies' => ['button']],
        ],
    ]);

    expect($registry->registryDependenciesFor('badge'))->toBe(['button']);
});

it('returns an empty array when the component has no registryDependencies key', function (): void {
    $registry = registryWithFixture([
        'items' => [['name' => 'button']],
    ]);

    expect($registry->registryDependenciesFor('button'))->toBe([]);
});

it('returns an empty array for registry dependencies when the component does not exist', function (): void {
    $registry = registryWithFixture(['items' => []]);

    expect($registry->registryDependenciesFor('unknown'))->toBe([]);
});

// ── srcPath() ─────────────────────────────────────────────────────────────────

it('returns the configured src path', function (): void {
    $registry = new Registry(srcPath: '/my/src');

    expect($registry->srcPath())->toBe('/my/src');
});

// ── Helpers ───────────────────────────────────────────────────────────────────

function registryWithFixture(array $data): Registry
{
    $path = tempnam(sys_get_temp_dir(), 'registry_').'.json';
    file_put_contents($path, json_encode($data));

    return new Registry(registryPath: $path);
}
