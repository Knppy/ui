<?php

declare(strict_types=1);

namespace Knppy\Ui;

use Illuminate\Support\Arr;
use Psr\SimpleCache\CacheInterface;
use Stringable;
use TailwindMerge\TailwindMerge;

class ClassBuilder implements Stringable
{
    /**
     * List of pending classes.
     */
    protected array $pending = [];

    /**
     * Constructor.
     *
     * @param  array<string, mixed>  $configurations
     */
    public function __construct(
        private readonly array $configurations = [],
        private readonly ?CacheInterface $cache = null,
    ) {}

    /**
     * String representation of this class builder.
     */
    public function __toString(): string
    {
        return $this->twMerge($this->pending);
    }

    /**
     * Tailwind merge.
     *
     * @param  string|array<array-key, string|array<array-key, string>>  ...$args
     */
    public function twMerge(...$args): string
    {
        return new TailwindMerge($this->configurations, $this->cache)->merge(...$args);
    }

    /**
     * Add classes to this class builder.
     *
     * @param  array<string,>|array<int,string|int>|string  $classes
     */
    public function add(array|string $classes): ClassBuilder
    {
        $clone = clone $this;

        $clone->pending[] = Arr::toCssClasses($classes);

        return $clone;
    }
}
