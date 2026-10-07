<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Knppy\Ui\Registry\Builder;

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

it('installs javascript files into the js alias keeping their subdirectories', function () {
    $root = sys_get_temp_dir().'/knppy-root-'.uniqid();
    File::ensureDirectoryExists($root.'/registry/js/ui');
    File::ensureDirectoryExists($root.'/registry/ui/x');
    File::put($root.'/registry/js/ui/x.js', 'js');
    File::put($root.'/registry/ui/x/x.blade.php', 'blade');
    File::put($root.'/registry/registry.json', json_encode(['items' => [[
        'name' => 'x',
        'files' => ['registry/ui/x/x.blade.php', 'registry/js/ui/x.js'],
    ]]]));

    try {
        (new Builder)->build($root, $root.'/out');
        $files = json_decode(File::get($root.'/out/x.json'), true)['files'];
    } finally {
        File::deleteDirectory($root);
    }

    expect($files[0])->toMatchArray(['path' => 'x.blade.php', 'type' => 'registry:ui'])
        ->and($files[1])->toMatchArray(['path' => 'ui/x.js', 'type' => 'registry:js']);
});

it('ships the Alpine module and base item for every component that uses Alpine data', function () {
    $root = __DIR__.'/../../registry';
    $modules = [];

    foreach (File::glob($root.'/js/ui/*.js') as $module) {
        preg_match_all("/Alpine\.(?:data|store|magic)\('(\w+)'/", File::get($module), $names);

        foreach ($names[1] as $name) {
            $modules[$name] = 'registry/js/ui/'.basename($module);
        }
    }

    foreach (json_decode(File::get($root.'/registry.json'), true)['items'] as $item) {
        $blade = collect($item['files'])->filter(fn (string $file): bool => str_ends_with($file, '.blade.php'))
            ->map(fn (string $file): string => File::get(__DIR__.'/../../'.$file))->implode("\n");

        preg_match_all('/x-data="(ui\w+)/', $blade, $used);

        foreach (array_unique($used[1]) as $name) {
            expect($modules)->toHaveKey($name)
                ->and($item['files'])->toContain($modules[$name])
                ->and($item['registryDependencies'])->toContain('alpine');
        }
    }
});
