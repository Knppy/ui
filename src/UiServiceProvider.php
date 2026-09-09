<?php

declare(strict_types=1);

namespace Knppy\Ui;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use Knppy\Ui\Console\Commands\AddCommand;
use Knppy\Ui\Console\Commands\InstallCommand;
use Psr\SimpleCache\CacheInterface;
use TailwindMerge\Support\Config;
use TailwindMerge\TailwindMerge;

class UiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ui.php', 'ui');

        $this->app->singleton(ClassBuilder::class);
        $this->app->singleton(TailwindMerge::class, function () {
            Config::setAdditionalConfig(config('ui.twMerge', []));

            return new TailwindMerge(
                Config::getMergedConfig(),
                $this->getCacheStore(),
            );
        });
        $this->app->singleton(Ui::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootComponentsPath();
        $this->bootAttributesBagMacros();
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

    /**
     * Boot attributes bag macro's
     */
    private function bootAttributesBagMacros(): void
    {
        ComponentAttributeBag::macro('twMerge', function (...$args): ComponentAttributeBag {
            /** @var ComponentAttributeBag $this */
            $this->offsetSet('class', resolve(TailwindMerge::class)
                ->merge($args, ($this->get('class', ''))));

            return $this;
        });
    }
}
