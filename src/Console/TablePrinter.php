<?php

namespace LaravelDoctor\Console;

use Illuminate\Console\Command;
use LaravelDoctor\Support\CheckResult;

class TablePrinter
{
    
    public static function print(Command $command, array $results): void
    {
        $headers = ['Check', 'Status', 'Severity', 'Message', 'Repair Available?'];
        $rows = [];

        foreach ($results as $result) {
            $statusLabel = match ($result->getStatus()) {
                'success' => '✔ PASSED',
                'fail' => '✘ FAILED',
                'warning' => '⚠ WARNING',
                default => $result->getStatus(),
            };

            $statusFormatted = match ($result->getStatus()) {
                'success' => "<info>{$statusLabel}</info>",
                'fail' => "<error>{$statusLabel}</error>",
                'warning' => "<comment>{$statusLabel}</comment>",
                default => $statusLabel,
            };

            $repairLabel = $result->isRepairAvailable() 
                ? "<info>Yes ({$result->getRepairCommand()})</info>" 
                : 'No';

            $rows[] = [
                $result->getCheckName(),
                $statusFormatted,
                strtoupper($result->getSeverity()->value),
                $result->getMessage(),
                $repairLabel,
            ];
        }

        $command->table($headers, $rows);
    }
}
