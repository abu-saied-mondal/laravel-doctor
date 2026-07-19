<?php

namespace LaravelDoctor\Commands;

use Illuminate\Console\Command;

class DoctorCommand extends Command
{
    
    protected $signature = 'doctor';

    protected $description = 'Surgically inspect and repair your Laravel application';

    public function handle(): int
    {
        $this->newLine();
        $this->line(" <info>🩺 Laravel Doctor</info> — Diagnostics & Repair Toolkit");
        $this->line(" Laravel Doctor doesn't just tell you what's broken. It explains why it's broken and heals it.");
        $this->newLine();

        $choice = $this->choice('What action would you like to perform?', [
            '1' => 'Run Diagnostics Scan',
            '2' => 'Attempt Auto-Repairs',
            '3' => 'Verify Production Readiness',
            '4' => 'Exit',
        ], '1');

        if ($choice === '1' || $choice === 'Run Diagnostics Scan') {
            return $this->call('doctor:scan');
        }

        if ($choice === '2' || $choice === 'Attempt Auto-Repairs') {
            return $this->call('doctor:repair');
        }

        if ($choice === '3' || $choice === 'Verify Production Readiness') {
            return $this->call('doctor:production');
        }

        $this->info("Stay healthy! Goodbye.");
        return 0;
    }
}
