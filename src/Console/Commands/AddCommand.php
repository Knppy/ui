<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'ui:add')]
class AddCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:add {components?*} {--multiple} {--all} {--force}';

    /**
     * The command description.
     */
    protected $description = 'Add individual UI components.';

    /**
     * A list with all the ui components.
     *
     * @var array<string, string>
     */
    protected array $uiComponents = [

    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('Ui placeholder command executed.');

        return self::SUCCESS;
    }

    /**
     * @return Collection<string, string>
     */
    protected function uiComponents(): Collection
    {
        return collect($this->uiComponents)
            ->sortKeys();
    }
}
