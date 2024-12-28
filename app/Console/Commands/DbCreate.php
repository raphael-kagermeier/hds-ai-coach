<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbCreate extends Command
{
    protected $signature = 'db:create {name?}';

    protected $description = 'Create a new database based on the database config file or the provided name';

    public function handle(): void
    {
        $connection = config('database.default');
        $schema_name = $this->argument('name') ?: config("database.connections.{$connection}.database");

        // Temporarily connect to 'postgres' database for PostgreSQL or null for MySQL
        if ($connection === 'pgsql') {
            config(["database.connections.{$connection}.database" => 'postgres']);
        } else {
            config(["database.connections.{$connection}.database" => null]);
        }

        // Reconnect to apply new configuration
        DB::purge($connection);
        DB::reconnect($connection);

        try {
            if ($connection === 'pgsql') {
                // For PostgreSQL, we connect to 'postgres' database first
                $exists = DB::select(
                    'SELECT 1 FROM pg_database WHERE datname = ?',
                    [$schema_name]
                );

                if (empty($exists)) {
                    // Use parameterized identifier for safety
                    DB::statement('CREATE DATABASE "'.$schema_name.'"');
                }
            } else {
                $charset = config("database.connections.{$connection}.charset", 'utf8mb4');
                $collation = config("database.connections.{$connection}.collation", 'utf8mb4_unicode_ci');

                DB::statement("CREATE DATABASE IF NOT EXISTS `$schema_name` CHARACTER SET $charset COLLATE $collation");
            }

            $this->info("Database '$schema_name' created successfully.");
        } catch (\Exception $e) {
            $this->error('Failed to create database: '.$e->getMessage());

            return;
        } finally {
            // Restore the database name in config
            config(["database.connections.{$connection}.database" => $schema_name]);
            DB::purge($connection);
        }
    }
}
