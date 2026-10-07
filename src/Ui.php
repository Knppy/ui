<?php

declare(strict_types=1);

namespace Knppy\Ui;

class Ui
{
    /**
     * Get the shared class builder, optionally seeded with initial classes.
     *
     * @param  array<string, bool>|array<int, string|int>|string|null  $styles  Classes to add, in the same format as Arr::toCssClasses().
     */
    public function classes(array|string|null $styles = null): ClassBuilder
    {
        $builder = app(ClassBuilder::class);

        return $styles ? $builder->add($styles) : $builder;
    }
}
