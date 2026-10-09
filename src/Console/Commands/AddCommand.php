<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;
use Knppy\Ui\Registry\Installer;
use Knppy\Ui\Registry\InstallResult;
use Knppy\Ui\Registry\PackageInstaller;
use Knppy\Ui\Registry\Registry;
use Knppy\Ui\Registry\RegistryException;
use Knppy\Ui\Registry\RegistryListing;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\multisearch;

#[AsCommand('ui:add', 'Adds Knppy UI components to your project.')]
class AddCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:add
        {components?* : Component names, e.g. button or @registry/button (omit to search)}
        {--all : Add every component of the default registry}
        {--overwrite : Overwrite files that already exist}
        {--skip-packages : Do not install the required Composer and npm packages}
    ';

    /**
     * The command description.
     */
    protected $description = 'Adds Knppy UI components to your project.';

    /**
     * Execute the console command.
     */
    public function handle(Registry $registry, PackageInstaller $packages): int
    {
        try {
            $installer = Installer::fromProject($registry);

            $components = $this->selectComponents($installer->registry());

            if ($components === []) {
                $this->components->error('No components given. Pass component names, use --all, or run interactively.');

                return self::FAILURE;
            }

            $result = new InstallResult;

            foreach ($components as $component) {
                $this->components->info("Adding $component...");

                $result = $result->merge($installer->install($component, (bool) $this->option('overwrite')));
            }
        } catch (RegistryException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->report($result);

        return $this->installPackages($result, $packages);
    }

    /**
     * Resolve the component references from the arguments, --all or an interactive search.
     *
     * @return list<string>
     *
     * @throws RegistryException
     */
    private function selectComponents(Registry $registry): array
    {
        $given = array_values(array_filter((array) $this->argument('components'), is_string(...)));

        if ($given !== [] && ! $this->option('all')) {
            return $given;
        }

        if (! $this->option('all') && ! $this->input->isInteractive()) {
            return [];
        }

        $names = array_map(fn (RegistryListing $item): string => $item->name, $registry->list());

        if ($this->option('all')) {
            return $names;
        }

        $selected = multisearch(
            'Which components would you like to add?',
            fn (string $value): array => array_values(array_filter(
                $names,
                fn (string $name): bool => $value === '' || str_contains($name, strtolower($value)),
            )),
            scroll: 15,
        );

        return array_values(array_map(strval(...), $selected));
    }

    /**
     * Print what was installed and which packages still need to be required.
     */
    private function report(InstallResult $result): void
    {
        if ($this->output->isVerbose()) {
            foreach ($result->fallbacks as $name) {
                $this->components->warn("Remote registry unavailable, used bundled registry for $name.");
            }
        }

        foreach ($result->written as $path) {
            $this->line("   ✓ $path created.");
        }

        foreach ($result->skipped as $path) {
            $this->line("   - $path already exists, skipped (use --overwrite).");
        }

        foreach ($result->written as $path) {
            if (str_ends_with($path, '/ui.js')) {
                $this->components->info("Import it once in your app.js to register the Alpine components: import './ui.js';");
            }
        }
    }

    /**
     * Install the Composer and npm packages the project is still missing.
     */
    private function installPackages(InstallResult $result, PackageInstaller $packages): int
    {
        $composer = $packages->missingComposer($result->composerDependencies);
        $npm = $packages->missingNpm($result->npmDependencies);

        $skip = (bool) $this->option('skip-packages');
        $succeeded = true;

        if ($composer !== []) {
            $install = $skip ? null : fn () => $packages->installComposer($composer);

            $succeeded = $this->runInstall('Composer', 'composer require '.implode(' ', $composer), $install);
        }

        if ($npm !== []) {
            $install = $skip || ! $packages->hasPackageJson() ? null : fn () => $packages->installNpm($npm);

            $succeeded = $this->runInstall('npm', 'npm install '.implode(' ', $npm), $install) && $succeeded;
        }

        return $succeeded ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Run an install callback, or print the manual command when there is none.
     *
     * @param  (callable(): bool)|null  $install
     */
    private function runInstall(string $label, string $command, ?callable $install): bool
    {
        if ($install === null) {
            $this->components->info("Install the required {$label} packages:");
            $this->line("   {$command}");

            return true;
        }

        $this->components->info("Installing {$label} packages...");

        if ($install()) {
            $this->line("   ✓ {$command}");

            return true;
        }

        $this->components->error("Installing {$label} packages failed. Run manually:");
        $this->line("   {$command}");

        return false;
    }
}
