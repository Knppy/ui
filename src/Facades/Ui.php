<?php

declare(strict_types=1);

namespace Knppy\Ui\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Knppy\Ui\Ui
 */
class Ui extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Knppy\Ui\Ui::class;
    }
}
