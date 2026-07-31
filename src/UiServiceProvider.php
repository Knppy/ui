<?php

declare(strict_types=1);

namespace Knppy\Ui;

use Illuminate\Support\ServiceProvider;
use Knppy\Ui\Console\Commands\UiCommand;

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

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/ui.php' => config_path('ui.php'),
        ], ['ui', 'ui-config']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/ui'),
        ], ['ui', 'ui-lang']);

        $this->commands([
            UiCommand::class,
        ]);
    }
}
