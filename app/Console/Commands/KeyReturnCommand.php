<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\KeyGenerateCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'key:return')]
class KeyReturnCommand extends KeyGenerateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key:return';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and return an application key without modifying files';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $key = $this->generateRandomKey();
        $this->line('<comment>'.$key.'</comment>');
    }
}
