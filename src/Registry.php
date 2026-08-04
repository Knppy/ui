<?php

declare(strict_types=1);

namespace Knppy\Ui;

use RuntimeException;

class Registry
{
    private string $srcPath;

    /** @var array{name:string, homepage:string, description:string, items:array} */
    private array $registry;

    public function __construct(?string $registryPath = null, ?string $srcPath = null)
    {
        $this->srcPath = $srcPath ?? dirname(__DIR__).'/registry';

        $registryPath ??= dirname(__DIR__).'/registry/registry.json';

        if (! is_file($registryPath)) {
            throw new RuntimeException(sprintf('Registry file not found: %s', $registryPath));
        }

        $decodedRegistry = json_decode(file_get_contents($registryPath), true);

        if (! is_array($decodedRegistry)) {
            throw new RuntimeException(sprintf('Registry file is not a valid JSON array: %s', $registryPath));
        }

        $this->registry = $decodedRegistry;
    }

    /**
     * Get a single component by component name.
     */
    public function component(string $componentName): ?array
    {
        return array_first(array_filter($this->items(), fn (array $item) => $item['name'] === $componentName));
    }

    /**
     * Determine if a component exists.
     */
    public function componentExists(string $componentName): bool
    {
        return in_array($componentName, array_map(fn (array $item) => $item['name'] ?? null, $this->items()));
    }

    /**
     * Get a list of all the components.
     */
    public function components(): array
    {
        return array_map(fn (array $item) => $item['name'], $this->items());
    }

    /**
     * Get the dependencies for a component by component name.
     *
     * @return array{composer:list<string>, npm:list<string>}
     */
    public function dependenciesFor(string $componentName): array
    {
        return $this->component($componentName)['dependencies'] ?? [];
    }

    /**
     * Get the files for a component by component name.
     */
    public function filesFor(string $componentName): array
    {
        return $this->component($componentName)['files'] ?? [];
    }

    /**
     * Get the list with items.
     */
    public function items(): array
    {
        return $this->registry['items'] ?? [];
    }

    /**
     * Get the registry dependencies by component name.
     */
    public function registryDependenciesFor(string $componentName): array
    {
        return $this->component($componentName)['registryDependencies'] ?? [];
    }

    /**
     * Get the src path of the registry.
     */
    public function srcPath(): string
    {
        return $this->srcPath;
    }
}
