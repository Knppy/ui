<?php

declare(strict_types=1);

namespace Ui\Ui\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Ui\Ui\ColorScheme;

#[AsCommand('ui:install', 'Installs Knppy UI into your project.')]
class InstallCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:install
        {--baseColor= : Base color scheme}
        {--force : Override existing configuration}
    ';

    /**
     * The command description.
     */
    protected $description = 'Installs Knppy UI into your project.';

    /**
     * The base color scheme selected for this installation.
     */
    private ColorScheme $baseColor;

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle(): int
    {
        $this->components->info('Installing Knppy UI into your project...');

        // Check if the components.json exists.
        $componentsJsonPath = base_path('components.json');

        if (File::exists($componentsJsonPath) && ! $this->option('force')) {
            $this->components->error('Project already initialized. Use --force to reinitialize.');

            return self::FAILURE;
        }

        // Select a base color.
        $this->baseColor = $this->selectBaseColor();

        // Generate the components.json file.
        $this->createComponentsJson();

        // Generate the app.css file.
        $this->createAppCss();

        return self::SUCCESS;
    }

    /**
     * Create a components.json file.
     *
     * @throws FileNotFoundException
     */
    private function createComponentsJson(): void
    {
        $this->components->info('Creating components.json with color: '.$this->baseColor->value);

        $stub = File::get(__DIR__.'/../../../stubs/components.json');

        $fileContent = Str::of($stub)
            ->replace('{{BASE_COLOR}}', $this->baseColor->value);

        File::put(base_path('components.json'), $fileContent->value());

        $this->line('   ✓ Components.json created successfully.');
    }

    /**
     * Create a app.css file.
     *
     * @throws FileNotFoundException
     */
    private function createAppCss(): void
    {
        $this->components->info('Creating resources/css/app.css with color: '.$this->baseColor->value);

        $stub = File::get(__DIR__.'/../../../stubs/app.css');

        $fileContent = Str::of($stub)
            ->replace('{{THEME_VARIABLES}}', $this->baseColor->getAppCssVariables());

        File::put(resource_path('css/app.css'), $fileContent->value());

        $this->line('   ✓ app.css created successfully.');
    }

    /**
     * Select a base color.
     */
    private function selectBaseColor(): ColorScheme
    {
        $baseColor = $this->option('baseColor');

        if (is_string($baseColor) && $baseColor !== '') {
            if ($colorScheme = ColorScheme::tryFrom($baseColor)) {
                return $colorScheme;
            }

            $this->components->warn('Base color is unavailable.');
        }

        $choice = $this->choice(
            'Which base color would you like to use?',
            array_map(fn (ColorScheme $colorScheme) => $colorScheme->value, ColorScheme::cases()),
            0,
        );

        return ColorScheme::from(is_string($choice) ? $choice : ColorScheme::NEUTRAL->value);
    }
}
