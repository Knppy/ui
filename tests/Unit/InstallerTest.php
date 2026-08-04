<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Knppy\Ui\Installer;
use Knppy\Ui\Registry;

// ── install() ────────────────────────────────────────────────────────────────

it('installs a component file to the target path', function (): void {
    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    $targetPath = sys_get_temp_dir().'/knppy_target_'.uniqid();

    File::makeDirectory($srcPath.'/ui/button', 0755, true);
    File::put($srcPath.'/ui/button/button.blade.php', '<div>button</div>');
    File::makeDirectory($targetPath, 0755, true);

    $registry = installerRegistryWithFixture(['items' => []], srcPath: $srcPath);
    $installer = new Installer($targetPath, false, $registry);

    $installed = $installer->install(['files' => ['ui/button/button.blade.php']]);

    expect($installed)->toBe([$targetPath.'/button.blade.php'])
        ->and(File::get($targetPath.'/button.blade.php'))->toBe('<div>button</div>');
});

it('installs multiple files and returns all destination paths', function (): void {
    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    $targetPath = sys_get_temp_dir().'/knppy_target_'.uniqid();

    File::makeDirectory($srcPath.'/ui/button', 0755, true);
    File::put($srcPath.'/ui/button/button.blade.php', '<div>button</div>');
    File::put($srcPath.'/ui/button/button.js', 'console.log("button")');
    File::makeDirectory($targetPath, 0755, true);

    $registry = installerRegistryWithFixture(['items' => []], srcPath: $srcPath);
    $installer = new Installer($targetPath, false, $registry);

    $installed = $installer->install([
        'files' => ['ui/button/button.blade.php', 'ui/button/button.js'],
    ]);

    expect($installed)->toHaveCount(2)
        ->and($installed)->toContain($targetPath.'/button.blade.php')
        ->and($installed)->toContain($targetPath.'/button.js');
});

it('returns an empty array when the component has no files', function (): void {
    $targetPath = sys_get_temp_dir().'/knppy_target_'.uniqid();
    File::makeDirectory($targetPath, 0755, true);

    $registry = installerRegistryWithFixture(['items' => []]);
    $installer = new Installer($targetPath, false, $registry);

    expect($installer->install(['files' => []]))->toBe([]);
});

it('creates the target directory if it does not exist', function (): void {
    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    $targetPath = sys_get_temp_dir().'/knppy_target_'.uniqid().'/nested';

    File::makeDirectory($srcPath.'/ui/button', 0755, true);
    File::put($srcPath.'/ui/button/button.blade.php', '<div>button</div>');

    $registry = installerRegistryWithFixture(['items' => []], srcPath: $srcPath);
    $installer = new Installer($targetPath, false, $registry);

    $installer->install(['files' => ['ui/button/button.blade.php']]);

    expect(File::isDirectory($targetPath))->toBeTrue();
});

it('throws when the destination file already exists and force is false', function (): void {
    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    $targetPath = sys_get_temp_dir().'/knppy_target_'.uniqid();

    File::makeDirectory($srcPath.'/ui/button', 0755, true);
    File::put($srcPath.'/ui/button/button.blade.php', '<div>button</div>');
    File::makeDirectory($targetPath, 0755, true);
    File::put($targetPath.'/button.blade.php', 'existing');

    $registry = installerRegistryWithFixture(['items' => []], srcPath: $srcPath);
    $installer = new Installer($targetPath, false, $registry);

    $installer->install(['files' => ['ui/button/button.blade.php']]);
})->throws(RuntimeException::class, 'File already exists');

it('overwrites an existing file when force is true', function (): void {
    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    $targetPath = sys_get_temp_dir().'/knppy_target_'.uniqid();

    File::makeDirectory($srcPath.'/ui/button', 0755, true);
    File::put($srcPath.'/ui/button/button.blade.php', '<div>new</div>');
    File::makeDirectory($targetPath, 0755, true);
    File::put($targetPath.'/button.blade.php', '<div>old</div>');

    $registry = installerRegistryWithFixture(['items' => []], srcPath: $srcPath);
    $installer = new Installer($targetPath, true, $registry);

    $installer->install(['files' => ['ui/button/button.blade.php']]);

    expect(File::get($targetPath.'/button.blade.php'))->toBe('<div>new</div>');
});

// ── Helpers ───────────────────────────────────────────────────────────────────

function installerRegistryWithFixture(array $data, ?string $srcPath = null): Registry
{
    $path = tempnam(sys_get_temp_dir(), 'registry_').'.json';
    file_put_contents($path, json_encode($data));

    return new Registry(registryPath: $path, srcPath: $srcPath);
}
