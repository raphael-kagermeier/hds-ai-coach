<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExtensionInstall extends Command
{
    protected $signature = 'db:extension-install';

    protected $description = 'Installs necessary PostgreSQL extensions';

    protected const REQUIRED_EXTENSIONS = [
        'vector',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing PostgreSQL extensions...');

        try {
            foreach (self::REQUIRED_EXTENSIONS as $extension) {
                $this->installExtension($extension);
            }

            $this->info('All extensions installed successfully!');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to install extensions: {$e->getMessage()}");

            return Command::FAILURE;
        }
    }

    private function installExtension(string $extension_name): void
    {
        $this->line("Installing {$extension_name}...");

        DB::statement("CREATE EXTENSION IF NOT EXISTS \"{$extension_name}\"");

        $this->info("✓ {$extension_name} installed");
    }
}
