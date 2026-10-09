<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Knppy\Ui\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * Build a registry item payload.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function registryPayload(string $name, array $overrides = []): array
{
    return [
        'name' => $name,
        'type' => 'registry:ui',
        'files' => [['path' => "{$name}.blade.php", 'type' => 'registry:ui', 'content' => "{$name} content"]],
        ...$overrides,
    ];
}

/**
 * Write a components.json into the current base path.
 *
 * @param  array<string, mixed>  $overrides
 */
function writeComponentsJson(array $overrides = []): void
{
    File::put(base_path('components.json'), json_encode([
        'aliases' => ['components' => 'resources/views/components', 'ui' => 'resources/views/components/ui', 'js' => 'resources/js'],
        ...$overrides,
    ], JSON_THROW_ON_ERROR));
}

/**
 * Make the registry's Blade sources renderable as <x-ui.*> components.
 *
 * Mirrors what `ui:add` does: every source file is copied flat into a
 * `ui` directory that is registered as an anonymous component path.
 */
function mountRegistryComponents(): void
{
    static $root = null;

    if ($root === null) {
        $root = sys_get_temp_dir().'/knppy-components-'.uniqid();
        File::ensureDirectoryExists("{$root}/ui");

        foreach (File::allFiles(dirname(__DIR__).'/registry/ui') as $file) {
            File::copy($file->getPathname(), "{$root}/ui/{$file->getFilename()}");
        }

        register_shutdown_function(fn () => File::deleteDirectory($root));
    }

    Blade::anonymousComponentPath($root);
}
