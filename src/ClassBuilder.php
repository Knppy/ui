<?php

declare(strict_types=1);

namespace Ui\Ui;

use Illuminate\Support\Arr;
use Stringable;

/**
 * Immutable builder that collects CSS classes into a single string.
 */
class ClassBuilder implements Stringable
{
    /**
     * List of pending classes.
     *
     * @var list<string>
     */
    protected array $pending = [];

    /**
     * String representation of this class builder.
     */
    public function __toString(): string
    {
        return collect($this->pending)->join(' ');
    }

    /**
     * Add classes to this class builder.
     *
     * @param  array<string, bool>|array<int, string|int>|string  $classes
     */
    public function add(array|string $classes): ClassBuilder
    {
        $clone = clone $this;

        $clone->pending[] = Arr::toCssClasses($classes);

        return $clone;
    }
}
