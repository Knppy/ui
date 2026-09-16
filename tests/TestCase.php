<?php

declare(strict_types=1);

namespace Knppy\Ui\Tests;

use Knppy\Ui\UiServiceProvider;
use Livewire\Blaze\BlazeServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            UiServiceProvider::class,
            BlazeServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }
}
