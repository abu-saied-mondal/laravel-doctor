<?php

namespace LaravelDoctor\Checks;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use Illuminate\Contracts\Container\Container;

class PermissionCheck implements Check
{
    public function __construct(protected Container $container) {}

    public function check(): CheckResult
    {
        $app = $this->container->make('app');

        $storagePath = $app->storagePath();
        $bootstrapCachePath = $app->bootstrapPath() . '/cache';

        $errors = [];

        if (!is_writable($storagePath)) {
            $errors[] = "Storage directory is not writable.";
        }

        if (file_exists($bootstrapCachePath) && !is_writable($bootstrapCachePath)) {
            $errors[] = "Bootstrap cache directory is not writable.";
        }

        if (!empty($errors)) {
            return CheckResult::fail(
                'Directory Permissions',
                implode(' ', $errors),
                'Ensure the directories are writable by the webserver user (e.g., chmod -R 775 storage bootstrap/cache).',
                false,
                null,
                Severity::CRITICAL
            );
        }

        return CheckResult::success('Directory Permissions', 'Required storage and cache directories are writable.');
    }
}
