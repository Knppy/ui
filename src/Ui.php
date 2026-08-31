<?php

declare(strict_types=1);

namespace Knppy\Ui;

class Ui
{
    public function classes(array|string|null $styles = null): ClassBuilder
    {
        $builder = app(ClassBuilder::class);

        return $styles ? $builder->add($styles) : $builder;
    }
}
