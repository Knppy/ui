<?php

declare(strict_types=1);

namespace Knppy\Ui;

use Illuminate\Support\ServiceProvider;
use Knppy\Ui\Console\Commands\AddCommand;
use Knppy\Ui\Console\Commands\InstallCommand;

class UiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ui.php', 'ui');

        $this->app->singleton(Ui::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'ui');

        $this->bootPublishResources();
        $this->bootCommands();
    }

    /**
     * Boot any application publishable resources.
     */
    private function bootPublishResources(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/ui.php' => config_path('ui.php'),
        ], ['ui', 'ui-config']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/ui'),
        ], ['ui', 'ui-lang']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/ui'),
        ], ['ui', 'ui-assets']);
    }

    /**
     * Boot any application commands.
     */
    private function bootCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            AddCommand::class,
            InstallCommand::class,
        ]);
    }
}
