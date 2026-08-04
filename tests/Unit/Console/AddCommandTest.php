<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Knppy\Ui\DependencyResolver;
use Knppy\Ui\Registry;

use function Pest\Laravel\artisan;

// File-system state at base_path() is shared across parallel workers.
// These tests must run serially to avoid race conditions on components.json.
uses()->group('serial');

// ── helpers ───────────────────────────────────────────────────────────────────

/**
 * Build a temporary registry backed by real blade source files.
 *
 * @param  array<int, array<string, mixed>>  $items
 */
function addCommandRegistry(array $items, string $srcPath): Registry
{
    $data = ['name' => 'knppy', 'homepage' => '', 'description' => '', 'items' => $items];
    $registryPath = tempnam(sys_get_temp_dir(), 'registry_').'.json';
    file_put_contents($registryPath, json_encode($data));

    return new Registry(registryPath: $registryPath, srcPath: $srcPath);
}

/**
 * Write a components.json to base_path() and return its path.
 *
 * The 'ui' alias must be a path relative to base_path() because the command
 * always calls base_path($config['aliases']['ui']).
 */
function writeComponentsJson(array $config = []): string
{
    $path = base_path('components.json');
    File::put($path, json_encode($config));

    return $path;
}

/**
 * Populate the fake registry source tree with blade stubs.
 *
 * @param  array<string>  $files  registry-relative file paths, e.g. ['ui/button.blade.php']
 */
function populateSrc(string $srcPath, array $files): void
{
    foreach ($files as $file) {
        $full = $srcPath.'/'.$file;
        File::makeDirectory(dirname($full), 0755, true, true);
        File::put($full, "<!-- {$file} -->");
    }
}

/**
 * Bind a fresh registry + resolver into the container.
 */
function bindRegistry(Registry $registry): void
{
    app()->instance(Registry::class, $registry);
    app()->instance(DependencyResolver::class, new DependencyResolver($registry));
}

function cleanWorkbenchState(): void
{
    foreach (['components.json', 'composer.json', 'package.json'] as $file) {
        $path = base_path($file);

        if (File::exists($path)) {
            File::delete($path);
        }
    }

    if (File::isDirectory(base_path('resources'))) {
        File::deleteDirectory(base_path('resources'));
    }
}

beforeEach(function (): void {
    cleanWorkbenchState();
});

afterEach(function (): void {
    cleanWorkbenchState();
});

// ── not installed ─────────────────────────────────────────────────────────────

it('fails when components.json does not exist', function (): void {
    artisan('ui:add', ['components' => ['button']])
        ->assertFailed()
        ->expectsOutputToContain('Project not installed');
});

// ── no components given ───────────────────────────────────────────────────────

it('shows an error when no components are specified', function (): void {
    writeComponentsJson();

    artisan('ui:add')
        ->assertSuccessful()
        ->expectsOutputToContain('Component(s) specified');
});

// ── unknown component ─────────────────────────────────────────────────────────

it('outputs an error and continues when a component is not in the registry', function (): void {
    writeComponentsJson();

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    bindRegistry(addCommandRegistry([], $srcPath));

    artisan('ui:add', ['components' => ['unknown']])
        ->assertSuccessful()
        ->expectsOutputToContain('Component not found in registry: unknown');
});

// ── happy path: single component ──────────────────────────────────────────────

it('installs a component and outputs the relative file path', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => [],
        'files' => ['ui/button.blade.php'],
    ]];

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button']])
        ->assertSuccessful()
        ->expectsOutputToContain('button.blade.php');

    expect(File::exists(base_path("{$alias}/button.blade.php")))->toBeTrue();
});

// ── --all flag ────────────────────────────────────────────────────────────────

it('installs all registry components when --all is passed', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php', 'ui/badge.blade.php']);

    $items = [
        ['name' => 'button', 'registryDependencies' => [], 'dependencies' => [], 'files' => ['ui/button.blade.php']],
        ['name' => 'badge', 'registryDependencies' => [], 'dependencies' => [], 'files' => ['ui/badge.blade.php']],
    ];

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['--all' => true])
        ->assertSuccessful()
        ->expectsOutputToContain('button.blade.php')
        ->expectsOutputToContain('badge.blade.php');
});

// ── dependency deduplication ──────────────────────────────────────────────────

it('installs a shared dependency only once when two components require it', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php', 'ui/button-group.blade.php']);

    $items = [
        ['name' => 'button', 'registryDependencies' => [], 'dependencies' => [], 'files' => ['ui/button.blade.php']],
        ['name' => 'button-group', 'registryDependencies' => ['button'], 'dependencies' => [], 'files' => ['ui/button-group.blade.php']],
    ];

    bindRegistry(addCommandRegistry($items, $srcPath));

    // Requesting both explicitly — button must only be written once (no RuntimeException from --force=false).
    artisan('ui:add', ['components' => ['button-group', 'button']])
        ->assertSuccessful();

    expect(File::exists(base_path("{$alias}/button.blade.php")))->toBeTrue()
        ->and(File::exists(base_path("{$alias}/button-group.blade.php")))->toBeTrue();
});

// ── installer exception ───────────────────────────────────────────────────────

it('outputs an error and continues when the installer throws', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    // Pre-create the file so the installer throws without --force.
    $dest = base_path("{$alias}/button.blade.php");
    File::makeDirectory(dirname($dest), 0755, true);
    File::put($dest, 'existing');

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => [],
        'files' => ['ui/button.blade.php'],
    ]];

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button']])
        ->assertSuccessful()
        ->expectsOutputToContain('File already exists');
});

// ── --force flag ──────────────────────────────────────────────────────────────

it('overwrites existing files when --force is passed', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $dest = base_path("{$alias}/button.blade.php");
    File::makeDirectory(dirname($dest), 0755, true);
    File::put($dest, 'old');

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => [],
        'files' => ['ui/button.blade.php'],
    ]];

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button'], '--force' => true])
        ->assertSuccessful();

    expect(File::get($dest))->toBe('<!-- ui/button.blade.php -->');
});

// ── default target path ───────────────────────────────────────────────────────

it('defaults to resources/views/components/ui when aliases.ui is absent', function (): void {
    writeComponentsJson([]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => [],
        'files' => ['ui/button.blade.php'],
    ]];

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button']])
        ->assertSuccessful();

    expect(File::exists(base_path('resources/views/components/ui/button.blade.php')))->toBeTrue();
});

// ── composer dependencies: all new ────────────────────────────────────────────

it('outputs "Installing composer packages" for new composer packages', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => ['composer' => ['vendor/new-pkg'], 'npm' => []],
        'files' => ['ui/button.blade.php'],
    ]];

    bindRegistry(addCommandRegistry($items, $srcPath));

    // No composer.json at base_path → package is "new".
    artisan('ui:add', ['components' => ['button']])
        ->assertSuccessful()
        ->expectsOutputToContain('Installing composer packages');
});

// ── composer dependencies: existing, user declines ───────────────────────────

it('skips composer update when user declines', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => ['composer' => ['vendor/existing-pkg'], 'npm' => []],
        'files' => ['ui/button.blade.php'],
    ]];

    File::put(base_path('composer.json'), json_encode([
        'require' => ['vendor/existing-pkg' => '^1.0'],
    ]));

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button']])
        ->expectsConfirmation('The following composer packages are already installed: vendor/existing-pkg. Update them?', 'no')
        ->assertSuccessful()
        ->doesntExpectOutputToContain('Updating composer packages');
});

// ── composer dependencies: existing, user confirms all ───────────────────────

it('runs composer update when user confirms', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => ['composer' => ['vendor/existing-pkg'], 'npm' => []],
        'files' => ['ui/button.blade.php'],
    ]];

    File::put(base_path('composer.json'), json_encode([
        'require' => ['vendor/existing-pkg' => '^1.0'],
    ]));

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button']])
        ->expectsConfirmation('The following composer packages are already installed: vendor/existing-pkg. Update them?', 'yes')
        ->expectsChoice('Select which composer packages to update', ['vendor/existing-pkg'], ['vendor/existing-pkg'])
        ->assertSuccessful()
        ->expectsOutputToContain('Updating composer packages');
});

// ── composer: packages in require-dev are treated as existing ─────────────────

it('detects packages declared in require-dev as already installed', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => ['composer' => ['vendor/dev-pkg'], 'npm' => []],
        'files' => ['ui/button.blade.php'],
    ]];

    File::put(base_path('composer.json'), json_encode([
        'require-dev' => ['vendor/dev-pkg' => '^2.0'],
    ]));

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button']])
        ->expectsConfirmation('The following composer packages are already installed: vendor/dev-pkg. Update them?', 'no')
        ->assertSuccessful()
        ->doesntExpectOutputToContain('Installing composer packages');
});

// ── npm dependencies: all new ─────────────────────────────────────────────────

it('outputs "Installing npm packages" for new npm packages', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => ['composer' => [], 'npm' => ['some-lib']],
        'files' => ['ui/button.blade.php'],
    ]];

    bindRegistry(addCommandRegistry($items, $srcPath));

    // No package.json → package is "new".
    artisan('ui:add', ['components' => ['button']])
        ->assertSuccessful()
        ->expectsOutputToContain('Installing npm packages');
});

// ── npm dependencies: existing, user declines ─────────────────────────────────

it('skips npm update when user declines', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => ['composer' => [], 'npm' => ['some-lib']],
        'files' => ['ui/button.blade.php'],
    ]];

    File::put(base_path('package.json'), json_encode([
        'dependencies' => ['some-lib' => '^1.0'],
    ]));

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button']])
        ->expectsConfirmation('The following npm packages are already installed: some-lib. Update them?', 'no')
        ->assertSuccessful()
        ->doesntExpectOutputToContain('Updating npm packages');
});

// ── npm dependencies: existing, user confirms ────────────────────────────────

it('runs npm update when user confirms', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => ['composer' => [], 'npm' => ['some-lib']],
        'files' => ['ui/button.blade.php'],
    ]];

    File::put(base_path('package.json'), json_encode([
        'dependencies' => ['some-lib' => '^1.0'],
    ]));

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button']])
        ->expectsConfirmation('The following npm packages are already installed: some-lib. Update them?', 'yes')
        ->expectsChoice('Select which npm packages to update', ['some-lib'], ['some-lib'])
        ->assertSuccessful()
        ->expectsOutputToContain('Updating npm packages');
});

// ── npm: packages in devDependencies are treated as existing ──────────────────

it('detects packages declared in devDependencies as already installed', function (): void {
    $alias = 'resources/views/components/ui';
    writeComponentsJson(['aliases' => ['ui' => $alias]]);

    $srcPath = sys_get_temp_dir().'/knppy_src_'.uniqid();
    populateSrc($srcPath, ['ui/button.blade.php']);

    $items = [[
        'name' => 'button',
        'registryDependencies' => [],
        'dependencies' => ['composer' => [], 'npm' => ['dev-lib']],
        'files' => ['ui/button.blade.php'],
    ]];

    File::put(base_path('package.json'), json_encode([
        'devDependencies' => ['dev-lib' => '^2.0'],
    ]));

    bindRegistry(addCommandRegistry($items, $srcPath));

    artisan('ui:add', ['components' => ['button']])
        ->expectsConfirmation('The following npm packages are already installed: dev-lib. Update them?', 'no')
        ->assertSuccessful()
        ->doesntExpectOutputToContain('Installing npm packages');
});
