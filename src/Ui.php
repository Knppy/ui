<?php

declare(strict_types=1);

namespace Knppy\Ui;

class Ui
{
    /**
     * @param  array{nonce?: string}  $options
     */
    public function scripts(array $options = []): string
    {
        return AssetManager::scripts($options);
    }

    /**
     * @param  array<string, bool>|array<int, string|int>|string|null  $styles
     */
    public function classes(array|string|null $styles = null): ClassBuilder
    {
        $builder = app(ClassBuilder::class);

        return $styles ? $builder->add($styles) : $builder;
    }
}
