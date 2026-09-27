<?php

declare(strict_types=1);

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\File;
use Knppy\Ui\Console\Commands\AddCommand;

/**
 * The real stub views ship with the package, so tests register a fixture
 * subset of them as the command's known component groups instead of
 * depending on the full (still unpublished) component catalog.
 */
function withComponentGroups(Application $app, array $componentGroups): void
{
    $app->extend(AddCommand::class, function (AddCommand $command) use ($componentGroups): AddCommand {
        Closure::bind(function () use ($componentGroups): void {
            $this->componentGroups = $componentGroups;
        }, $command, AddCommand::class)();

        return $command;
    });
}

function stubPath(string $group, string $component): string
{
    return dirname(__DIR__, 4)."/stubs/views/components/{$group}/{$component}.blade.php";
}

beforeEach(function (): void {
    $this->app->setBasePath(storage_path('framework/testing/ui-app-'.getmypid()));

    foreach (['ui', 'block'] as $group) {
        if (File::isDirectory(resource_path("views/components/{$group}"))) {
            File::deleteDirectory(resource_path("views/components/{$group}"));
        }
    }
});

afterEach(function (): void {
    foreach (['ui', 'block'] as $group) {
        if (File::isDirectory(resource_path("views/components/{$group}"))) {
            File::deleteDirectory(resource_path("views/components/{$group}"));
        }
    }
});

test('publishes a component passed as an argument', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button']]]);

    $this->artisan('ui:add', ['components' => ['button']])
        ->assertSuccessful();

    expect(File::exists(resource_path('views/components/ui/button.blade.php')))->toBeTrue();
    expect(File::get(resource_path('views/components/ui/button.blade.php')))
        ->toBe(File::get(stubPath('ui', 'button')));
});

test('publishes multiple components passed as arguments', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button'], 'badge' => ['badge']]]);

    $this->artisan('ui:add', ['components' => ['button', 'badge']])
        ->assertSuccessful();

    expect(File::exists(resource_path('views/components/ui/button.blade.php')))->toBeTrue();
    expect(File::exists(resource_path('views/components/ui/badge.blade.php')))->toBeTrue();
});

test('matches component arguments case-insensitively', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button']]]);

    $this->artisan('ui:add', ['components' => ['BUTTON']])
        ->assertSuccessful();

    expect(File::exists(resource_path('views/components/ui/button.blade.php')))->toBeTrue();
});

test('ignores unknown component arguments', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button']]]);

    $this->artisan('ui:add', ['components' => ['does-not-exist']])
        ->assertSuccessful();

    expect(File::isDirectory(resource_path('views/components/ui')))->toBeFalse();
});

test('publishes every known component with the --all flag', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button'], 'badge' => ['badge']]]);

    $this->artisan('ui:add', ['--all' => true])
        ->assertSuccessful();

    expect(File::exists(resource_path('views/components/ui/button.blade.php')))->toBeTrue();
    expect(File::exists(resource_path('views/components/ui/badge.blade.php')))->toBeTrue();
});

test('publishes every real ui stub file with --all, keeping the catalog in sync with the stubs directory', function (): void {
    $this->artisan('ui:add', ['--all' => true])
        ->assertSuccessful();

    $expectedFiles = collect(File::files(dirname(__DIR__, 4).'/stubs/views/components/ui'))
        ->map(fn ($file) => $file->getFilename())
        ->sort()
        ->values();

    $publishedFiles = collect(File::files(resource_path('views/components/ui')))
        ->map(fn ($file) => $file->getFilename())
        ->sort()
        ->values();

    expect($publishedFiles->all())->toBe($expectedFiles->all());
});

test('publishes the component selected via interactive search', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button'], 'badge' => ['badge']]]);

    $this->artisan('ui:add')
        ->expectsSearch('Which component would you like to publish?', 'button', 'but', ['button'])
        ->assertSuccessful();

    expect(File::exists(resource_path('views/components/ui/button.blade.php')))->toBeTrue();
});

test('publishes the components selected via interactive multisearch with --multiple', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button'], 'badge' => ['badge']]]);

    $this->artisan('ui:add', ['--multiple' => true])
        ->expectsSearch(
            'Which component would you like to publish?',
            ['button', 'badge'],
            '',
            ['button', 'badge'],
        )
        ->assertSuccessful();

    expect(File::exists(resource_path('views/components/ui/button.blade.php')))->toBeTrue();
    expect(File::exists(resource_path('views/components/ui/badge.blade.php')))->toBeTrue();
});

test('outputs a published message for each published component', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button']]]);

    $this->artisan('ui:add', ['components' => ['button']])
        ->expectsOutputToContain('Published: resources/views/components/ui/button.blade.php')
        ->assertSuccessful();
});

test('does not overwrite an existing file without --force', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button']]]);

    File::ensureDirectoryExists(resource_path('views/components/ui'));
    File::put(resource_path('views/components/ui/button.blade.php'), 'custom content');

    $this->artisan('ui:add', ['components' => ['button']])
        ->expectsOutputToContain('Skipping [button]. File already exists')
        ->assertSuccessful();

    expect(File::get(resource_path('views/components/ui/button.blade.php')))->toBe('custom content');
});

test('overwrites an existing file with --force', function (): void {
    withComponentGroups($this->app, ['ui' => ['button' => ['button']]]);

    File::ensureDirectoryExists(resource_path('views/components/ui'));
    File::put(resource_path('views/components/ui/button.blade.php'), 'custom content');

    $this->artisan('ui:add', ['components' => ['button'], '--force' => true])
        ->assertSuccessful();

    expect(File::get(resource_path('views/components/ui/button.blade.php')))
        ->toBe(File::get(stubPath('ui', 'button')));
});

describe('block components', function (): void {
    beforeEach(function (): void {
        File::ensureDirectoryExists(dirname(stubPath('block', 'hero')));
        File::put(stubPath('block', 'hero'), '<div>Hero block</div>'.PHP_EOL);
    });

    afterEach(function (): void {
        File::delete(stubPath('block', 'hero'));
    });

    test('publishes a component from the block group', function (): void {
        withComponentGroups($this->app, ['block' => ['hero' => ['hero']]]);

        $this->artisan('ui:add', ['components' => ['hero']])
            ->assertSuccessful();

        expect(File::exists(resource_path('views/components/block/hero.blade.php')))->toBeTrue();
        expect(File::get(resource_path('views/components/block/hero.blade.php')))
            ->toBe(File::get(stubPath('block', 'hero')));
        expect(File::isDirectory(resource_path('views/components/ui')))->toBeFalse();
    });

    test('publishes components spanning both groups in a single command', function (): void {
        withComponentGroups($this->app, [
            'ui' => ['button' => ['button']],
            'block' => ['hero' => ['hero']],
        ]);

        $this->artisan('ui:add', ['components' => ['button', 'hero']])
            ->assertSuccessful();

        expect(File::exists(resource_path('views/components/ui/button.blade.php')))->toBeTrue();
        expect(File::exists(resource_path('views/components/block/hero.blade.php')))->toBeTrue();
    });

    test('publishes components from every group with the --all flag', function (): void {
        withComponentGroups($this->app, [
            'ui' => ['button' => ['button']],
            'block' => ['hero' => ['hero']],
        ]);

        $this->artisan('ui:add', ['--all' => true])
            ->assertSuccessful();

        expect(File::exists(resource_path('views/components/ui/button.blade.php')))->toBeTrue();
        expect(File::exists(resource_path('views/components/block/hero.blade.php')))->toBeTrue();
    });
});
