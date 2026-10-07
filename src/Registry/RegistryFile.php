<?php

declare(strict_types=1);

namespace Knppy\Ui\Registry;

final readonly class RegistryFile
{
    public function __construct(
        public string $path,
        public string $type,
        public string $content,
    ) {
        $segments = explode('/', str_replace('\\', '/', $path));

        if ($path === '' || str_starts_with($path, '/') || in_array('..', $segments, true)) {
            throw RegistryException::unsafePath($path);
        }
    }
}
