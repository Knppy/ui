<?php

declare(strict_types=1);

namespace Knppy\Ui\Registry;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class PackageInstaller
{
    /**
     * Get the Composer packages that are not yet required by the project.
     *
     * @param  list<string>  $packages  Package names, optionally with a constraint (acme/pkg:^1.0).
     * @return list<string>
     */
    public function missingComposer(array $packages): array
    {
        return $this->missing($packages, base_path('composer.json'), ['require', 'require-dev'], ':');
    }

    /**
     * Get the npm packages that are not yet listed in the project's package.json.
     *
     * @param  list<string>  $packages  Package names, optionally with a version (alpinejs@^3).
     * @return list<string>
     */
    public function missingNpm(array $packages): array
    {
        return $this->missing($packages, base_path('package.json'), ['dependencies', 'devDependencies'], '@');
    }

    /**
     * Whether the project has a package.json to install npm packages into.
     */
    public function hasPackageJson(): bool
    {
        return File::isFile(base_path('package.json'));
    }

    /**
     * Run composer require for the packages. Returns whether it succeeded.
     *
     * @param  list<string>  $packages
     */
    public function installComposer(array $packages): bool
    {
        return $this->run(['composer', 'require', ...$packages]);
    }

    /**
     * Run npm install for the packages. Returns whether it succeeded.
     *
     * @param  list<string>  $packages
     */
    public function installNpm(array $packages): bool
    {
        return $this->run(['npm', 'install', ...$packages]);
    }

    /**
     * Run a command in the project root.
     *
     * @param  list<string>  $command
     */
    private function run(array $command): bool
    {
        return Process::path(base_path())->timeout(600)->run($command)->successful();
    }

    /**
     * Filter out packages that are already listed under the given manifest sections.
     *
     * @param  list<string>  $packages
     * @param  list<string>  $sections
     * @return list<string>
     */
    private function missing(array $packages, string $manifest, array $sections, string $versionSeparator): array
    {
        $json = File::isFile($manifest) ? json_decode(File::get($manifest), true) : null;

        $installed = [];

        foreach ($sections as $section) {
            if (is_array($json) && is_array($json[$section] ?? null)) {
                $installed = [...$installed, ...array_keys($json[$section])];
            }
        }

        return array_values(array_filter(
            $packages,
            fn (string $package): bool => ! in_array($this->name($package, $versionSeparator), $installed, true),
        ));
    }

    /**
     * Strip the version constraint from a package specifier (scoped npm names start with @).
     */
    private function name(string $package, string $versionSeparator): string
    {
        $offset = str_starts_with($package, '@') ? 1 : 0;
        $position = strpos($package, $versionSeparator, $offset);

        return $position === false ? $package : substr($package, 0, $position);
    }
}
