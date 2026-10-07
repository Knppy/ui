<?php

declare(strict_types=1);

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
