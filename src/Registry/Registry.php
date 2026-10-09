<?php

declare(strict_types=1);

namespace Knppy\Ui\Registry;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use JsonException;

class Registry
{
    /**
     * @param  array<string, string>  $registries  Namespace (e.g. @knppy) => URL template or local directory.
     * @param  array<string, string>  $fallbacks  Namespace => local directory used when a remote registry fails.
     */
    public function __construct(
        private readonly array $registries,
        private readonly string $default = '@knppy',
        private readonly array $fallbacks = [],
    ) {}

    /**
     * Get a copy of this registry with extra registries merged over the existing ones.
     *
     * @param  array<string, string>  $registries
     */
    public function withRegistries(array $registries): self
    {
        return new self([...$this->registries, ...$registries], $this->default, $this->fallbacks);
    }

    /**
     * Resolve a single item by name or @registry/name.
     *
     * @throws RegistryException
     */
    public function resolve(string $reference): RegistryItem
    {
        [$namespace, $name] = $this->parse($reference, $this->default);

        return $this->resolveIn($namespace, $name);
    }

    /**
     * Resolve an item and its registry dependencies, dependencies first.
     *
     * @return list<RegistryItem>
     *
     * @throws RegistryException
     */
    public function resolveWithDependencies(string $reference): array
    {
        $resolved = [];

        $this->collect($reference, $this->default, $resolved, []);

        return array_values($resolved);
    }

    /**
     * Depth-first collection of an item and its dependencies.
     *
     * @param  array<string, RegistryItem>  $resolved
     * @param  list<string>  $stack
     */
    private function collect(string $reference, string $defaultNamespace, array &$resolved, array $stack): void
    {
        [$namespace, $name] = $this->parse($reference, $defaultNamespace);

        $key = $namespace.'/'.$name;

        if (isset($resolved[$key]) || in_array($key, $stack, true)) {
            return;
        }

        $item = $this->resolveIn($namespace, $name);

        foreach ($item->registryDependencies as $dependency) {
            $this->collect($dependency, $namespace, $resolved, [...$stack, $key]);
        }

        $resolved[$key] = $item;
    }

    /**
     * Split a reference into [namespace, name].
     *
     * @return array{string, string}
     *
     * @throws RegistryException
     */
    private function parse(string $reference, string $defaultNamespace): array
    {
        if (preg_match('/^(@[\w.-]+)\/([\w.-]+)$/', $reference, $matches) === 1) {
            return [$matches[1], $matches[2]];
        }

        if (preg_match('/^[\w.-]+$/', $reference) === 1) {
            return [$defaultNamespace, $reference];
        }

        throw RegistryException::invalidReference($reference);
    }

    /**
     * Fetch an item from a registry, falling back to the bundled one if allowed.
     *
     * @throws RegistryException
     */
    private function resolveIn(string $namespace, string $name): RegistryItem
    {
        $template = $this->registries[$namespace] ?? throw RegistryException::unknownRegistry($namespace);

        $location = $this->locate($template, $name);

        $isRemote = Str::startsWith($location, ['http://', 'https://']);

        try {
            return RegistryItem::fromArray(
                $this->fetch($location, $isRemote),
                $isRemote ? RegistrySource::Remote : RegistrySource::Local,
                $location,
            );
        } catch (RegistryException $exception) {
            $fallback = $this->fallbacks[$namespace] ?? null;

            if (! $isRemote || $fallback === null) {
                throw $exception;
            }

            $fallbackLocation = rtrim($fallback, '/').'/'.$name.'.json';

            try {
                return RegistryItem::fromArray(
                    $this->fetch($fallbackLocation, false),
                    RegistrySource::Fallback,
                    $fallbackLocation,
                );
            } catch (RegistryException) {
                throw $exception;
            }
        }
    }

    /**
     * List the items of a registry from its registry.json index, falling back to the bundled one if allowed.
     *
     * @return list<RegistryListing>
     *
     * @throws RegistryException
     */
    public function list(?string $namespace = null): array
    {
        $namespace ??= $this->default;

        $template = $this->registries[$namespace] ?? throw RegistryException::unknownRegistry($namespace);

        $location = $this->locate($template, 'registry');
        $isRemote = Str::startsWith($location, ['http://', 'https://']);

        try {
            return $this->listing($this->fetch($location, $isRemote), $isRemote ? RegistrySource::Remote : RegistrySource::Local, $location);
        } catch (RegistryException $exception) {
            $fallback = $this->fallbacks[$namespace] ?? null;

            if (! $isRemote || $fallback === null) {
                throw $exception;
            }

            $fallbackLocation = rtrim($fallback, '/').'/registry.json';

            try {
                return $this->listing($this->fetch($fallbackLocation, false), RegistrySource::Fallback, $fallbackLocation);
            } catch (RegistryException) {
                throw $exception;
            }
        }
    }

    /**
     * Get the namespace used when a reference has none.
     */
    public function defaultNamespace(): string
    {
        return $this->default;
    }

    /**
     * Parse the items of a decoded registry index.
     *
     * @param  array<mixed>  $data
     * @return list<RegistryListing>
     *
     * @throws RegistryException
     */
    private function listing(array $data, RegistrySource $source, string $location): array
    {
        if (! is_array($data['items'] ?? null)) {
            throw RegistryException::invalidItem($location, 'missing items');
        }

        return array_values(array_map(
            fn (mixed $item): RegistryListing => RegistryListing::fromArray($item, $source, $location),
            $data['items'],
        ));
    }

    /**
     * Get the location of a named JSON file in a registry.
     */
    private function locate(string $template, string $name): string
    {
        return Str::contains($template, '{name}')
            ? Str::replace('{name}', $name, $template)
            : rtrim($template, '/').'/'.$name.'.json';
    }

    /**
     * Fetch and decode a registry item payload.
     *
     * @return array<mixed>
     *
     * @throws RegistryException
     */
    private function fetch(string $location, bool $remote): array
    {
        try {
            if ($remote) {
                $data = Http::acceptJson()->timeout(10)->get($location)->throw()->json();
            } elseif (File::isFile($location)) {
                $data = json_decode(File::get($location), true, 512, JSON_THROW_ON_ERROR);
            } else {
                throw RegistryException::unavailable($location);
            }
        } catch (ConnectionException|RequestException|JsonException $exception) {
            throw RegistryException::unavailable($location, $exception);
        }

        if (! is_array($data)) {
            throw RegistryException::invalidItem($location, 'not a JSON object');
        }

        return $data;
    }
}
