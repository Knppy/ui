<?php

declare(strict_types=1);

namespace Knppy\Ui;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Knppy\Ui\Console\Commands\AddCommand;
use Knppy\Ui\Console\Commands\InstallCommand;
use Psr\SimpleCache\CacheInterface;

class UiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ui.php', 'ui');

        $this->app->singleton(Ui::class);
        $this->app->singleton(ClassBuilder::class, function () {
            return new ClassBuilder(
                config('ui.twMerge', []),
                $this->getCacheStore(),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootComponentsPath();
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'ui');

        $this->bootPublishResources();
        $this->bootCommands();
    }

    /**
     * Boot the components path.
     */
    private function bootComponentsPath(): void
    {
        Blade::anonymousComponentPath(__DIR__.'/../stubs/views/components');
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

    /**
     * Get the cache store instance that will be used by Knppy UI.
     */
    private function getCacheStore(): CacheInterface
    {
        $storage = config('ui.cache_store');

        return Cache::store($storage);
    }
}
