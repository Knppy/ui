<?php

declare(strict_types=1);

namespace Knppy\Ui\Console\Commands;

use Illuminate\Console\Command;

class UiCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ui:placeholder';

    /**
     * The command description.
     */
    protected $description = 'Placeholder Artisan command shipped by the package ui.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('Ui placeholder command executed.');

        return self::SUCCESS;
    }
}
