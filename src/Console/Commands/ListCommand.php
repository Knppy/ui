<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;
use Knppy\Ui\Registry;

class ListCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:list
        {component? : Show details for a single component}';

    /**
     * The command description.
     */
    protected $description = 'List the available components';

    /**
     * Execute the console command.
     */
    public function handle(Registry $registry): int
    {
        if ($single = $this->argument('component')) {
            if (! $registry->componentExists($single)) {
                $this->components->error("Unknown component: $single");

                return self::FAILURE;
            }

            $this->components->info("Component: $single");
            $this->line('  <fg=gray>Files:</> '.implode(', ', array_map('basename', $registry->filesFor($single))));

            $deps = $registry->registryDependenciesFor($single);
            $this->line('  <fg=gray>Depends on:</> '.($deps ? implode(', ', $deps) : '-'));

            $dependencies = $registry->dependenciesFor($single);
            $composer = $dependencies['composer'] ?? [];
            $npm = $dependencies['npm'] ?? [];
            $this->line('  <fg=gray>Composer:</> '.($composer ? implode(', ', $composer) : '-'));
            $this->line('  <fg=gray>npm:</> '.($npm ? implode(', ', $npm) : '-'));

            $this->newLine();
            $this->line("  Install with: <fg=green>php artisan ui:add $single</>");

            return self::SUCCESS;
        }

        $components = $registry->components();
        $this->components->info(count($components).' components found');

        $rows = [];
        foreach ($components as $component) {
            $deps = $registry->registryDependenciesFor($component);
            $rows[] = [$component, $deps ? implode(', ', $deps) : '-'];
        }

        $this->table(['Component name', 'Depends on'], $rows);
        $this->line('  Add one with: <fg=green>php artisan ui:add <component_name></>');

        return self::SUCCESS;
    }
}
