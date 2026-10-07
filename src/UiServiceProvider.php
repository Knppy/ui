<?php

declare(strict_types=1);

namespace Ui\Ui;

use Illuminate\Support\ServiceProvider;
use Ui\Ui\Console\Commands\UiCommand;

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
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/ui.php' => config_path('ui.php'),
        ], ['ui', 'ui-config']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/ui'),
        ], ['ui', 'ui-assets']);

        $this->commands([
            UiCommand::class,
        ]);
    }
}
