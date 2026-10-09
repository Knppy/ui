<?php

declare(strict_types=1);

namespace Knppy\Ui\Tests;

use Illuminate\Support\Facades\File;
use Knppy\Ui\UiServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            UiServiceProvider::class,
        ];
    }

    /**
     * Make the registry's Blade sources renderable as <x-ui.*> components.
     *
     * Mirrors what `ui:add` does: every source file is copied flat into a
     * `components/ui` directory that is added as a view location.
     */
    protected function defineEnvironment($app): void
    {
        $sources = File::allFiles(dirname(__DIR__).'/registry/ui');
        $fingerprint = md5(collect($sources)->map(fn ($file) => $file->getPathname().$file->getMTime())->implode(''));
        $root = sys_get_temp_dir().'/knppy-components-'.$fingerprint;

        if (! File::isDirectory($root)) {
            $staging = $root.'-'.uniqid();
            File::ensureDirectoryExists("{$staging}/components/ui");

            foreach ($sources as $file) {
                File::copy($file->getPathname(), "{$staging}/components/ui/{$file->getFilename()}");
            }

            if (! @rename($staging, $root)) {
                File::deleteDirectory($staging);
            }
        }

        $app['view']->getFinder()->addLocation($root);
    }
}
