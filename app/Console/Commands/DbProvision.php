<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DbProvision extends Command
{
    protected $signature = 'db:provision';

    protected $description = 'Provision database by running create and extension installation';

    public function handle(): int
    {
        $this->info('Starting database provisioning...');

        // Run database creation
        $this->call('db:create');

        // Run extension installation
        $this->call('db:extension-install');

        $this->info('Database provisioning completed successfully.');

        return Command::SUCCESS;
    }
}
