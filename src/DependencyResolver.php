<?php

declare(strict_types=1);

namespace Knppy\Ui;

use RuntimeException;

class DependencyResolver
{
    public function __construct(protected Registry $registry) {}

    /**
     * Resolves the component name.
     */
    public function resolve(string $componentName): array
    {
        $tree = [];
        $queue = [$componentName];
        $visited = [];

        while (! empty($queue)) {
            $current = array_shift($queue);

            if (in_array($current, $visited)) {
                continue;
            }

            $visited[] = $current;
            $component = $this->registry->component($current);

            if (! $component) {
                throw new RuntimeException("Component not found in registry: {$current}");
            }

            $registryDependencies = $component['registryDependencies'] ?? [];
            foreach ($registryDependencies as $dependency) {
                if (! in_array($dependency, $visited)) {
                    array_unshift($queue, $dependency);
                }
            }

            $tree[] = $component;
        }

        return array_reverse($tree);
    }
}
