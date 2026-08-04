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
        foreach ($components as $componentName) {
            $this->installComponent($componentName, $registry, $dependencyResolver);
        }

        $this->components->success('Components installed successfully!');

        return self::SUCCESS;
    }

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
     * Install a single component.
     *
     * @throws FileNotFoundException
     */
    private function installComponent(string $componentName, Registry $registry, DependencyResolver $dependencyResolver): void
    {
        if (! $registry->componentExists($componentName)) {
            $this->error("   ✗ Component not found in registry: $componentName");

            return;
        }

        $resolvedComponent = $dependencyResolver->resolve($componentName);
        $config = $this->getConfig();
        $targetPath = base_path($config['aliases']['ui'] ?? 'resources/views/components/ui');

        $installer = new Installer($targetPath, $this->option('force'), $registry);
        foreach ($resolvedComponent as $component) {
            try {
                $files = $installer->install($component);

                foreach ($files as $file) {
                    $relativePath = str_replace(base_path().'/', '', $file);
                    $this->line("   ✓ {$relativePath}");
                }
            } catch (Exception $e) {
                $this->error("   ✗ {$e->getMessage()}");
            }
        }
    }
}
