<?php

declare(strict_types=1);

namespace Knppy\Ui\Registry;

final readonly class RegistryListing
{
    public function __construct(
        public string $name,
        public string $type,
        public string $title,
        public string $description,
        public RegistrySource $source,
    ) {}

    /**
     * Build a listing from a decoded registry index item.
     */
    public static function fromArray(mixed $data, RegistrySource $source, string $location): self
    {
        if (! is_array($data) || ! is_string($data['name'] ?? null) || $data['name'] === '') {
            throw RegistryException::invalidItem($location, 'missing name');
        }

        return new self(
            name: $data['name'],
            type: is_string($data['type'] ?? null) ? $data['type'] : 'registry:ui',
            title: is_string($data['title'] ?? null) ? $data['title'] : $data['name'],
            description: is_string($data['description'] ?? null) ? $data['description'] : '',
            source: $source,
        );
    }
}
