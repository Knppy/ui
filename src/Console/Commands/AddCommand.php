<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;
use Knppy\Ui\Registry\Installer;
use Knppy\Ui\Registry\InstallResult;
use Knppy\Ui\Registry\PackageInstaller;
use Knppy\Ui\Registry\Registry;
use Knppy\Ui\Registry\RegistryException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('ui:add', 'Adds Knppy UI components to your project.')]
class AddCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:add
        {components* : Component names, e.g. button or @registry/button}
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

            $result = new InstallResult;

            foreach ((array) $this->argument('components') as $component) {
                $this->components->info('Adding '.(is_string($component) ? $component : '').'...');

                $result = $result->merge($installer->install((string) $component, (bool) $this->option('overwrite')));
            }
        } catch (RegistryException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->report($result);

        return $this->installPackages($result, $packages);
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
