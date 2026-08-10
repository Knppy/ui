<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Knppy\Ui\Enums\ColorScheme;

class InstallCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:install
        {--force : Override existing configuration}';

    /**
     * The command description.
     */
    protected $description = 'Install the UI\'s resources and configurations';

    /**
     * The base color.
     */
    private ColorScheme $baseColor;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Check if already initialized
        $componentsJsonPath = base_path('components.json');

        if (File::exists($componentsJsonPath) && ! $this->option('force')) {
            $this->error('Project already initialized. Use --force to reinitialize.');

            return self::FAILURE;
        }

        // Select the base color
        $this->baseColor = $this->selectBaseColor();

        // Install dependencies.
        $this->updateDependencies();

        // Create components.json
        $this->createComponentsJson();

        // Create app.css
        $this->createAppCss();

        // Create app.js
        $this->createAppJs();

        // Display the success message and the next steps.
        $this->displaySuccessMessage();

        return self::SUCCESS;
    }

    /**
     * Displays the success message and the next steps.
     */
    private function displaySuccessMessage(): void
    {
        $this->newLine();
        $this->components->success('UI\'s resources and configurations installed successfully.');
        $this->newLine();

        $this->comment('Next steps:');
        $this->line('1. Add components:');
        $this->line('   php artisan ui:add button');
        $this->newLine();
        $this->line('2. Start using components:');
        $this->line('   <x-ui.button>Click me</x-ui.button>');
        $this->newLine();
    }

    /**
     * Select and return the base color.
     */
    private function selectBaseColor(): ColorScheme
    {
        $schemes = array_map(static fn (ColorScheme $colorScheme) => $colorScheme->value, ColorScheme::cases());
        $choice = $this->choice('Which base color would you like to use?', $schemes, 0);

        return ColorScheme::from(is_array($choice) ? $choice[0] : $choice);
    }

    /**
     * Creates the app.css into the resources/css directory.
     *
     * @throws FileNotFoundException
     */
    private function createAppCss(): void
    {
        $this->components->info('Creating app.css...');

        $stub = File::get(__DIR__.'/../../../resources/stubs/app.css');

        $stub = str_replace('{{THEME_COLOR}}', $this->baseColor->getCss(), $stub);

        File::put(resource_path('css/app.css'), $stub);

        $this->line("   ✓ Created app.css (base color: {$this->baseColor->value})");
    }

    /**
     * Creates the app.js into the resources/js directory.
     *
     * @throws FileNotFoundException
     */
    private function createAppJs(): void
    {
        $this->components->info('Creating app.js...');

        File::put(resource_path('js/ui-core.js'), File::get(__DIR__.'/../../../resources/stubs/ui-core.js'));
        File::put(resource_path('js/app.js'), File::get(__DIR__.'/../../../resources/stubs/app.js'));

        $this->line("   ✓ Created app.js (base color: {$this->baseColor->value})");
    }

    /**
     * Creates the components.json into the root directory.
     *
     * @throws FileNotFoundException
     */
    private function createComponentsJson(): void
    {
        $this->components->info('Creating components.json...');

        $stub = File::get(__DIR__.'/../../../resources/stubs/components.json');

        $stub = str_replace('{{BASE_COLOR}}', $this->baseColor->value, $stub);

        File::put(base_path('components.json'), $stub);

        $this->line("   ✓ Created components.json (base color: {$this->baseColor->value})");
    }

    /**
     * Update the dependencies.
     */
    private function updateDependencies(): void
    {
        $this->updateNodePackages(function (array $packages): array {
            return [
                '@alpinejs/anchor' => '^3.15.12',
                '@alpinejs/collapse' => '^3.15.12',
                '@alpinejs/focus' => '^3.15.12',
                'alpinejs' => '^3.15.12',
            ] + $packages;
        }, false);
    }

    /**
     * Update the dependencies in the "package.json" file.
     */
    protected static function updateNodePackages(callable $callback, bool $isDev = true): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $isDev ? 'devDependencies' : 'dependencies';

        $packages = json_decode((string) file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey,
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL,
        );
    }
}
