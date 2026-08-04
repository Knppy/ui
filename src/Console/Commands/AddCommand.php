<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Knppy\Ui\DependencyResolver;
use Knppy\Ui\Installer;
use Knppy\Ui\Registry;
use Symfony\Component\Process\Process;

class AddCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:add
        {components?* : Component(s), @namespace/name or URL for remote item(s)}
        {--all : Add every available component}
        {--force : Override existing configuration}';

    /**
     * The command description.
     */
    protected $description = 'Install the UI\'s resources and configurations';

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle(Registry $registry, DependencyResolver $dependencyResolver): int
    {
        if (! $this->isInstalled()) {
            $this->components->error('Project not installed. Run: php artisan ui:install');

            return self::FAILURE;
        }

        $components = $this->getComponentsToInstall($registry);

        if (empty($components)) {
            $this->components->error('Component(s) specified.');
        }

        $this->components->info('Installing components...');

        $resolvedComponents = $this->resolveComponents($components, $registry, $dependencyResolver);

        $composerPackages = [];
        $npmPackages = [];

        $this->installComponents($resolvedComponents, $registry, $composerPackages, $npmPackages);
        $this->installDependencies($composerPackages, $npmPackages);

        $this->components->success('Components installed successfully!');

        return self::SUCCESS;
    }

    /**
     * Determine if the project is installed by checking for the existence of the components.json file.
     */
    private function isInstalled(): bool
    {
        return File::exists(base_path('components.json'));
    }

    /**
     * Get a list of components to install.
     */
    private function getComponentsToInstall(Registry $registry): array
    {
        if ($this->option('all')) {
            return $registry->components();
        }

        return $this->argument('components', []);
    }

    /**
     * Get the components.json configuration file.
     *
     * @throws FileNotFoundException
     */
    private function getConfig(): array
    {
        $path = base_path('components.json');

        if (! File::exists($path)) {
            return [];
        }

        $content = File::get($path);

        return json_decode($content, true) ?? [];
    }

    /**
     * Install a list of components.
     *
     * @throws FileNotFoundException
     */
    private function installComponents(array $components, Registry $registry, array &$composerPackages, array &$npmPackages): void
    {
        $config = $this->getConfig();
        $targetPath = base_path($config['aliases']['ui'] ?? 'resources/views/components/ui');
        $installer = new Installer($targetPath, $this->option('force'), $registry);

        foreach ($components as $component) {
            try {
                $files = $installer->install($component);

                foreach ($files as $file) {
                    $relativePath = str_replace(base_path().'/', '', $file);
                    $this->line("   ✓ {$relativePath}");
                }
            } catch (Exception $e) {
                $this->error("   ✗ {$e->getMessage()}");
            }

            $dependencies = $registry->dependenciesFor($component['name']);
            $composerPackages = array_unique(array_merge($composerPackages, $dependencies['composer'] ?? []));
            $npmPackages = array_unique(array_merge($npmPackages, $dependencies['npm'] ?? []));
        }
    }

    /**
     * Install or optionally update composer and npm dependencies.
     */
    private function installDependencies(array $composerPackages, array $npmPackages): void
    {
        $this->installComposerDependencies($composerPackages);
        $this->installNpmDependencies($npmPackages);
    }

    /**
     * Install new composer packages and optionally update existing ones.
     */
    private function installComposerDependencies(array $packages): void
    {
        if (empty($packages)) {
            return;
        }

        $installed = $this->getInstalledComposerPackages();
        $new = array_values(array_filter($packages, fn (string $p) => ! isset($installed[$this->packageName($p)])));
        $existing = array_values(array_filter($packages, fn (string $p) => isset($installed[$this->packageName($p)])));

        if (! empty($new)) {
            $this->components->info('Installing composer packages...');
            $this->runProcess(['composer', 'require', ...$new]);
        }

        if (! empty($existing)) {
            $toUpdate = $this->askToUpdate('composer', $existing);

            if (! empty($toUpdate)) {
                $this->components->info('Updating composer packages...');
                $this->runProcess(['composer', 'update', ...$toUpdate]);
            }
        }
    }

    /**
     * Install new npm packages and optionally update existing ones.
     */
    private function installNpmDependencies(array $packages): void
    {
        if (empty($packages)) {
            return;
        }

        $installed = $this->getInstalledNpmPackages();
        $new = array_values(array_filter($packages, fn (string $p) => ! isset($installed[$this->packageName($p)])));
        $existing = array_values(array_filter($packages, fn (string $p) => isset($installed[$this->packageName($p)])));

        if (! empty($new)) {
            $this->components->info('Installing npm packages...');
            $this->runProcess(['npm', 'install', ...$new]);
        }

        if (! empty($existing)) {
            $toUpdate = $this->askToUpdate('npm', $existing);

            if (! empty($toUpdate)) {
                $this->components->info('Updating npm packages...');
                $this->runProcess(['npm', 'update', ...$toUpdate]);
            }
        }
    }

    /**
     * Ask the user which already-installed packages they want to update.
     *
     * @return array<string>
     */
    private function askToUpdate(string $manager, array $packages): array
    {
        $list = implode(', ', $packages);

        if (! $this->confirm("The following {$manager} packages are already installed: {$list}. Update them?", false)) {
            return [];
        }

        $choices = $this->choice(
            "Select which {$manager} packages to update",
            $packages,
            implode(',', array_keys($packages)),
            null,
            true,
        );

        return array_values(array_filter((array) $choices));
    }

    /**
     * Get installed packages from composer.json (require + require-dev).
     *
     * @return array<string, string>
     *
     * @throws FileNotFoundException
     */
    private function getInstalledComposerPackages(): array
    {
        $path = base_path('composer.json');

        if (! File::exists($path)) {
            return [];
        }

        $json = json_decode(File::get($path), true) ?? [];

        return array_merge($json['require'] ?? [], $json['require-dev'] ?? []);
    }

    /**
     * Get installed packages from package.json (dependencies + devDependencies).
     *
     * @return array<string, string>
     *
     * @throws FileNotFoundException
     */
    private function getInstalledNpmPackages(): array
    {
        $path = base_path('package.json');

        if (! File::exists($path)) {
            return [];
        }

        $json = json_decode(File::get($path), true) ?? [];

        return array_merge($json['dependencies'] ?? [], $json['devDependencies'] ?? []);
    }

    /**
     * Extract the bare package name from a constraint string (e.g. "vendor/pkg:^1.0" → "vendor/pkg").
     */
    private function packageName(string $package): string
    {
        return explode(':', $package)[0];
    }

    /**
     * Run a process command in the project root.
     *
     * @param  array<string>  $command
     */
    private function runProcess(array $command): void
    {
        $process = new Process($command, base_path());
        $process->setTty(false);
        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $this->components->error(sprintf('Command failed: %s', implode(' ', $command)));
        }
    }

    /**
     * Resolve a list of given components and their dependencies into a deduplicated list of components to install.
     */
    private function resolveComponents(array $components, Registry $registry, DependencyResolver $dependencyResolver): array
    {
        $resolved = [];
        foreach ($components as $componentName) {
            if (! $registry->componentExists($componentName)) {
                $this->error("   ✗ Component not found in registry: $componentName");

                continue;
            }

            foreach ($dependencyResolver->resolve($componentName) as $component) {
                $resolved[$component['name']] = $component;
            }
        }

        return $resolved;
    }
}
