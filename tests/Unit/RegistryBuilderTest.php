<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Ui\Ui\Registry\Builder;

it('builds items with the source file content inlined', function () {
    $output = sys_get_temp_dir().'/knppy-build-'.uniqid();

    (new Builder)->build(__DIR__.'/../..', $output);

    $item = json_decode(File::get($output.'/button.json'), true);
    File::deleteDirectory($output);

    expect($item['name'])->toBe('button')
        ->and($item['files'][0]['path'])->toBe('button.blade.php')
        ->and($item['files'][0]['content'])->toBe(File::get(__DIR__.'/../../registry/ui/button/button.blade.php'));
});

it('keeps the committed registry/r in sync with the sources', function () {
    $output = sys_get_temp_dir().'/knppy-build-'.uniqid();

    $written = (new Builder)->build(__DIR__.'/../..', $output);

    foreach ($written as $path) {
        expect(File::get(__DIR__.'/../../registry/r/'.basename($path)))
            ->toBe(File::get($path), 'Run composer registry:build.');
    }

    File::deleteDirectory($output);
});

it('fails when a listed source file is missing', function () {
    $root = sys_get_temp_dir().'/knppy-root-'.uniqid();
    File::ensureDirectoryExists($root.'/registry');
    File::put($root.'/registry/registry.json', json_encode(['items' => [['name' => 'x', 'files' => ['registry/ui/x/x.blade.php']]]]));

    try {
        (new Builder)->build($root, $root.'/out');
    } finally {
        File::deleteDirectory($root);
    }
})->throws(RuntimeException::class, 'could not be read');
