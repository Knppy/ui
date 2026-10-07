<?php

declare(strict_types=1);

namespace Knppy\Ui\Registry;

use JsonException;
use RuntimeException;

/**
 * Builds the distributable registry/r/{name}.json items (with inline file
 * content) from registry/registry.json and the source files it lists.
 */
class Builder
{
    /**
     * Build every item of the registry index.
     *
     * @param  string  $root  Package root containing registry/registry.json and the source files.
     * @param  string  $output  Directory the {name}.json files are written to.
     * @return list<string> The written file paths.
     *
     * @throws RuntimeException|JsonException
     */
    public function build(string $root, string $output): array
    {
        $index = json_decode($this->read($root.'/registry/registry.json'), true, 512, JSON_THROW_ON_ERROR);
        $items = is_array($index) && is_array($index['items'] ?? null) ? $index['items'] : [];

        if (! is_dir($output) && ! mkdir($output, 0755, true) && ! is_dir($output)) {
            throw new RuntimeException("Unable to create directory [{$output}].");
        }

        $written = [];

        foreach ($items as $item) {
            if (! is_array($item) || ! is_string($item['name'] ?? null) || ! is_array($item['files'] ?? null)) {
                throw new RuntimeException('Every registry item needs a name and files.');
            }

            $name = $item['name'];
            $type = is_string($item['type'] ?? null) ? $item['type'] : 'registry:ui';

            $item['files'] = array_map(fn (mixed $source): array => [
                'path' => $this->targetPath((string) $source, $name),
                'type' => $this->fileType((string) $source, $type),
                'content' => $this->read($root.'/'.$source),
            ], $item['files']);

            $path = $output.'/'.$name.'.json';

            file_put_contents($path, json_encode($item, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n");

            $written[] = $path;
        }

        return $written;
    }

    /**
     * Get the path of a source file relative to the alias it is installed in.
     */
    private function targetPath(string $source, string $name): string
    {
        if (str_starts_with($source, 'registry/js/')) {
            return substr($source, strlen('registry/js/'));
        }

        $prefix = "registry/ui/{$name}/";

        return str_starts_with($source, $prefix) ? substr($source, strlen($prefix)) : basename($source);
    }

    /**
     * Get the registry type of a file: JavaScript files are installed in the js alias.
     */
    private function fileType(string $source, string $itemType): string
    {
        return str_ends_with($source, '.js') ? 'registry:js' : $itemType;
    }

    /**
     * Read a file or fail loudly.
     *
     * @throws RuntimeException
     */
    private function read(string $path): string
    {
        $content = is_file($path) ? file_get_contents($path) : false;

        if ($content === false) {
            throw new RuntimeException("Registry source file [{$path}] could not be read.");
        }

        return $content;
    }
}
