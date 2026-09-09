<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Knppy\Ui\Enums\ColorScheme;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'ui:install')]
class InstallCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:install {--force} {--baseColor=}';

    /**
     * The command description.
     */
    protected $description = 'Quick install Knppy UI.';

    private ColorScheme $baseColor;

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle(): int
    {
        $this->line('Install Knppy UI');

        // Get base color selection.
        $this->baseColor = $this->selectBaseColor();

        // Copy app.css
        $this->updateAppCss();

        // Copy app.js
        $this->updateAppJs();

        return self::SUCCESS;
    }

    /**
     * Copy a simple stub.
     *
     * @throws FileNotFoundException
     */
    private function copyStub(string $path): void
    {
        $stub = Str::of(File::get(__DIR__.'/../../../stubs/'.$path));

        $destPath = resource_path($path);

        if (File::exists($destPath)) {
            if ($this->option('force') || $this->confirm("Update $destPath?", true)) {
                File::put($destPath, $stub->value());
                $this->line('   ✓ Updated '.$destPath);
            }

            return;
        }

        $destDir = dirname($destPath);

        if (! File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }
        File::put($destPath, $stub->value());
        $this->line('   ✓ Created '.$destPath);
    }

    /**
     * Select a base color.
     */
    private function selectBaseColor(): ColorScheme
    {
        if ($baseColor = $this->option('baseColor')) {
            return ColorScheme::from($baseColor);
        }

        $choice = $this->components->choice(
            'Select a base color',
            ColorScheme::cases(),
            0,
        );

        return ColorScheme::from($choice);
    }

    /**
     * Update app css and its dependencies.
     *
     * @throws FileNotFoundException
     */
    private function updateAppCss(): void
    {
        $this->components->info('Setting up theme...');

        $stub = Str::of(File::get(__DIR__.'/../../../stubs/css/app.css'))
            ->replace('{{THEME_VARIABLES}}', $this->baseColor->getThemeVariables());

        $appCssPath = resource_path('css/app.css');

        if (File::exists($appCssPath)) {
            if ($this->option('force') || $this->confirm("Update app.css with {$this->baseColor->value} theme?", true)) {
                File::put($appCssPath, $stub->value());
                $this->line('   ✓ Updated app.css');
            }

            return;
        }

        $cssDir = resource_path('css');

        if (! File::isDirectory($cssDir)) {
            File::makeDirectory($cssDir, 0755, true);
        }
        File::put($appCssPath, $stub->value());
        $this->line('   ✓ Created app.css');
    }

    /**
     * Update app js and its dependencies.
     *
     * @throws FileNotFoundException
     */
    private function updateAppJs(): void
    {
        $this->components->info('Setting up javascript...');

        $this->copyStub('js/ui.js');
        $this->copyStub('js/app.js');
    }
}
