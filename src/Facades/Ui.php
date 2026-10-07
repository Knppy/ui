<?php

declare(strict_types=1);

namespace Ui\Ui\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ui\Ui\Ui
 */
class Ui extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ui\Ui\Ui::class;
    }
}
