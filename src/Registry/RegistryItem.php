<?php

declare(strict_types=1);

namespace Ui\Ui\Registry;

final readonly class RegistryItem
{
    /**
     * @param  list<string>  $registryDependencies
     * @param  list<string>  $composerDependencies
     * @param  list<string>  $npmDependencies
     * @param  list<RegistryFile>  $files
     */
    public function __construct(
        public string $name,
        public string $type,
        public string $title,
        public string $description,
        public array $registryDependencies,
        public array $composerDependencies,
        public array $npmDependencies,
        public array $files,
        public RegistrySource $source,
    ) {}

    /**
     * Build an item from a decoded registry item payload.
     *
     * @param  array<mixed>  $data
     */
    public static function fromArray(array $data, RegistrySource $source, string $location): self
    {
        $name = $data['name'] ?? null;
        $files = $data['files'] ?? null;

        if (! is_string($name) || $name === '') {
            throw RegistryException::invalidItem($location, 'missing name');
        }

        if (! is_array($files) || $files === []) {
            throw RegistryException::invalidItem($location, 'missing files');
        }

        $type = is_string($data['type'] ?? null) ? $data['type'] : 'registry:ui';
        $dependencies = is_array($data['dependencies'] ?? null) ? $data['dependencies'] : [];

        return new self(
            name: $name,
            type: $type,
            title: is_string($data['title'] ?? null) ? $data['title'] : $name,
            description: is_string($data['description'] ?? null) ? $data['description'] : '',
            registryDependencies: self::strings($data['registryDependencies'] ?? null),
            composerDependencies: self::strings($dependencies['composer'] ?? null),
            npmDependencies: self::strings($dependencies['npm'] ?? null),
            files: array_values(array_map(
                fn (mixed $file): RegistryFile => self::file($file, $type, $location),
                $files,
            )),
            source: $source,
        );
    }

    /**
     * Build a file from a decoded registry file payload.
     */
    private static function file(mixed $file, string $itemType, string $location): RegistryFile
    {
        if (! is_array($file) || ! is_string($file['path'] ?? null) || ! is_string($file['content'] ?? null)) {
            throw RegistryException::invalidItem($location, 'files must have a path and content');
        }

        return new RegistryFile(
            $file['path'],
            is_string($file['type'] ?? null) ? $file['type'] : $itemType,
            $file['content'],
        );
    }

    /**
     * Keep only the string values of a decoded list.
     *
     * @return list<string>
     */
    private static function strings(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, is_string(...)));
    }
}
