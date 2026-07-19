<?php

namespace LaravelDoctor\Repairs;

use LaravelDoctor\Contracts\Repair;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Throwable;

class StorageRepair implements Repair
{
    public function __construct(protected ConsoleKernel $artisan) {}

    public function repair(): bool
    {
        try {
            $publicPath = function_exists('public_path') ? public_path() : base_path('public');
            $publicStoragePath = rtrim($publicPath, '/') . '/storage';

            if (file_exists($publicStoragePath) && !is_link($publicStoragePath)) {
                $backupPath = $publicStoragePath . '_backup_' . time();
                rename($publicStoragePath, $backupPath);
            }

            $exitCode = $this->artisan->call('storage:link');
            return $exitCode === 0;
        } catch (Throwable $e) {
            return false;
        }
    }
}
