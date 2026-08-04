<?php

declare(strict_types=1);

namespace Knppy\Ui;

use Illuminate\Support\ServiceProvider;
use Knppy\Ui\Console\Commands\AddCommand;
use Knppy\Ui\Console\Commands\InstallCommand;
use Knppy\Ui\Console\Commands\ListCommand;

class UiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ui.php', 'ui');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'ui');

        $this->publishes([
            __DIR__.'/../config/ui.php' => config_path('ui.php'),
        ], ['ui', 'ui-config']);

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/ui'),
        ], ['ui', 'ui-lang']);

        if ($this->app->runningInConsole()) {
            $this->commands([
                AddCommand::class,
                InstallCommand::class,
                ListCommand::class,
            ]);
        }
    }
}
