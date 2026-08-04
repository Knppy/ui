<?php

declare(strict_types=1);

use Knppy\Ui\DependencyResolver;
use Knppy\Ui\Registry;

// ── resolve() ────────────────────────────────────────────────────────────────

it('resolves a component with no registry dependencies', function (): void {
    $registry = resolverRegistryWithFixture([
        'items' => [
            ['name' => 'button', 'registryDependencies' => []],
        ],
    ]);

    $resolver = new DependencyResolver($registry);

    expect($resolver->resolve('button'))->toBe([
        ['name' => 'button', 'registryDependencies' => []],
    ]);
});

it('resolves a component with a single registry dependency', function (): void {
    $registry = resolverRegistryWithFixture([
        'items' => [
            ['name' => 'icon', 'registryDependencies' => []],
            ['name' => 'button', 'registryDependencies' => ['icon']],
        ],
    ]);

    $resolver = new DependencyResolver($registry);

    expect($resolver->resolve('button'))->toBe([
        ['name' => 'icon', 'registryDependencies' => []],
        ['name' => 'button', 'registryDependencies' => ['icon']],
    ]);
});

it('resolves transitive registry dependencies in leaf-first order', function (): void {
    $registry = resolverRegistryWithFixture([
        'items' => [
            ['name' => 'icon', 'registryDependencies' => []],
            ['name' => 'badge', 'registryDependencies' => ['icon']],
            ['name' => 'button', 'registryDependencies' => ['badge']],
        ],
    ]);

    $resolver = new DependencyResolver($registry);

    expect($resolver->resolve('button'))->toBe([
        ['name' => 'icon', 'registryDependencies' => []],
        ['name' => 'badge', 'registryDependencies' => ['icon']],
        ['name' => 'button', 'registryDependencies' => ['badge']],
    ]);
});

it('does not duplicate shared dependencies across multiple dependents', function (): void {
    $registry = resolverRegistryWithFixture([
        'items' => [
            ['name' => 'icon', 'registryDependencies' => []],
            ['name' => 'badge', 'registryDependencies' => ['icon']],
            ['name' => 'button', 'registryDependencies' => ['icon', 'badge']],
        ],
    ]);

    $resolver = new DependencyResolver($registry);

    $resolved = $resolver->resolve('button');

    $names = array_column($resolved, 'name');
    expect(array_count_values($names)['icon'])->toBe(1);
});

it('throws when the requested component does not exist', function (): void {
    $registry = resolverRegistryWithFixture(['items' => []]);

    $resolver = new DependencyResolver($registry);

    $resolver->resolve('unknown');
})->throws(RuntimeException::class, 'Component not found in registry: unknown');

it('throws when a registry dependency does not exist', function (): void {
    $registry = resolverRegistryWithFixture([
        'items' => [
            ['name' => 'button', 'registryDependencies' => ['missing']],
        ],
    ]);

    $resolver = new DependencyResolver($registry);

    $resolver->resolve('button');
})->throws(RuntimeException::class, 'Component not found in registry: missing');

it('handles a component with no registryDependencies key', function (): void {
    $registry = resolverRegistryWithFixture([
        'items' => [
            ['name' => 'button'],
        ],
    ]);

    $resolver = new DependencyResolver($registry);

    expect($resolver->resolve('button'))->toBe([['name' => 'button']]);
});

// ── Helpers ───────────────────────────────────────────────────────────────────

function resolverRegistryWithFixture(array $data): Registry
{
    $path = tempnam(sys_get_temp_dir(), 'registry_').'.json';
    file_put_contents($path, json_encode($data));

    return new Registry(registryPath: $path);
}
