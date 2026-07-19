<?php

namespace LaravelDoctor\Checks;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use Illuminate\Contracts\Container\Container;
use Throwable;

class DatabaseCheck implements Check
{
    public function __construct(protected Container $container) {}

    public function check(): CheckResult
    {
        if (!$this->container->bound('db')) {
            return CheckResult::fail(
                'Database Connection',
                'Database service is not bound to the container.',
                'Check database configurations and database provider registrations.',
                false,
                null,
                Severity::CRITICAL
            );
        }

        $db = $this->container->make('db');

        try {
            
            $db->connection()->getPdo();
        } catch (Throwable $e) {
            return CheckResult::fail(
                'Database Connection',
                "Cannot connect to the database: {$e->getMessage()}",
                'Ensure database server is running and database configuration credentials in .env are correct.',
                false,
                null,
                Severity::CRITICAL
            );
        }

        try {
            if ($this->container->bound('migrator')) {
                $migrator = $this->container->make('migrator');
                $files = $migrator->getMigrationFiles($migrator->paths());
                
                if ($migrator->repositoryExists()) {
                    $ran = $migrator->getRepository()->getRan();
                    $pending = array_diff(array_keys($files), $ran);

                    if (count($pending) > 0) {
                        return CheckResult::fail(
                            'Database Migrations',
                            'There are ' . count($pending) . ' pending database migrations.',
                            'Run "php artisan migrate" to apply pending migrations.',
                            true,
                            'migrate',
                            Severity::CRITICAL
                        );
                    }
                } else {
                    return CheckResult::fail(
                        'Database Migrations',
                        'Migrations repository table does not exist in the database.',
                        'Run "php artisan migrate" to initialize the database schema.',
                        true,
                        'migrate',
                        Severity::CRITICAL
                    );
                }
            }
        } catch (Throwable $e) {
            return CheckResult::warning(
                'Database Migrations',
                'Failed to check migrations: ' . $e->getMessage(),
                'Ensure database is fully initialized and migrations table is queryable.',
                false,
                null,
                Severity::HIGH
            );
        }

        return CheckResult::success('Database Connection', 'Database connection and migrations are up to date.');
    }
}
