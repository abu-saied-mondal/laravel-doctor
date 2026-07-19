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
            $exitCode = $this->artisan->call('storage:link');
            return $exitCode === 0;
        } catch (Throwable $e) {
            return false;
        }
    }
}
