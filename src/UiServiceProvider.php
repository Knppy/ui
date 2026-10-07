<?php

declare(strict_types=1);

namespace Ui\Ui;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use Psr\SimpleCache\CacheInterface;
use TailwindMerge\Support\Config;
use TailwindMerge\TailwindMerge;
use Ui\Ui\Console\Commands\AddCommand;
use Ui\Ui\Console\Commands\InstallCommand;
use Ui\Ui\Registry\Registry;

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

        $this->app->singleton(Registry::class, fn () => new Registry(
            registries: config('ui.registries', []),
            default: config('ui.default_registry', '@knppy'),
            fallbacks: config('ui.fallback', true) ? ['@knppy' => __DIR__.'/../registry/r'] : [],
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootAttributesBagMacros();

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
            AddCommand::class,
            InstallCommand::class,
        ]);
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

    /**
     * Get the cache store instance that will be used by Knppy UI.
     */
    private function getCacheStore(): CacheInterface
    {
        return Cache::memo(config('ui.cache_store'));
    }
}
