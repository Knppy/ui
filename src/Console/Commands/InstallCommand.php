<?php

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'ui:install')]
class InstallCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:install {--force}';

    /**
     * The command description.
     */
    protected $description = 'Quick install Knnpy UI.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('Ui placeholder command executed.');

        return self::SUCCESS;
    }
}
