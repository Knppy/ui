<?php

declare(strict_types=1);

namespace Knppy\Ui\Registry;

final readonly class InstallResult
{
    /**
     * @param  list<string>  $written  Project-relative paths that were written.
     * @param  list<string>  $skipped  Project-relative paths that already existed.
     * @param  list<string>  $composerDependencies
     * @param  list<string>  $npmDependencies
     * @param  list<string>  $fallbacks  Items served from the bundled registry because the remote one failed.
     */
    public function __construct(
        public array $written = [],
        public array $skipped = [],
        public array $composerDependencies = [],
        public array $npmDependencies = [],
        public array $fallbacks = [],
    ) {}

    /**
     * Combine two results, de-duplicating every list.
     */
    public function merge(self $other): self
    {
        return new self(
            array_values(array_unique([...$this->written, ...$other->written])),
            array_values(array_unique([...$this->skipped, ...$other->skipped])),
            array_values(array_unique([...$this->composerDependencies, ...$other->composerDependencies])),
            array_values(array_unique([...$this->npmDependencies, ...$other->npmDependencies])),
            array_values(array_unique([...$this->fallbacks, ...$other->fallbacks])),
        );
    }
}
