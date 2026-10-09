<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;
use Knppy\Ui\Registry\Installer;
use Knppy\Ui\Registry\Registry;
use Knppy\Ui\Registry\RegistryException;
use Knppy\Ui\Registry\RegistryListing;
use Knppy\Ui\Registry\RegistrySource;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('ui:list', 'Lists the components available in a registry.')]
class ListCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:list
        {registry? : Registry namespace, e.g. @knppy (defaults to the default registry)}
    ';

    /**
     * The command description.
     */
    protected $description = 'Lists the components available in a registry.';

    /**
     * Execute the console command.
     */
    public function handle(Registry $registry): int
    {
        $registry = Installer::projectRegistry($registry);

        $argument = $this->argument('registry');
        $namespace = is_string($argument) && $argument !== ''
            ? '@'.ltrim($argument, '@')
            : $registry->defaultNamespace();

        try {
            $items = $registry->list($namespace);
        } catch (RegistryException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }

        if ($items === []) {
            $this->components->info("No components found in $namespace.");

            return self::SUCCESS;
        }

        if ($this->output->isVerbose() && $items[0]->source === RegistrySource::Fallback) {
            $this->components->warn("Remote registry unavailable, listing the bundled registry for $namespace.");
        }

        $this->components->info("Components available in $namespace:");

        $this->table(
            ['Name', 'Type', 'Description'],
            array_map(fn (RegistryListing $item): array => [$namespace.'/'.$item->name, $item->type, $item->description], $items),
        );

        return self::SUCCESS;
    }
}
