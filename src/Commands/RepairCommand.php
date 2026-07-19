<?php

namespace LaravelDoctor\Commands;

use Illuminate\Console\Command;
use LaravelDoctor\Core\DoctorManager;
use LaravelDoctor\Contracts\Repair;
use Throwable;

class RepairCommand extends Command
{
    
    protected $signature = 'doctor:repair {--force : Apply all repairs without prompting}';

    protected $description = 'Attempt to repair common detected issues automatically';

    protected array $repairsMap = [
        'storage:link' => \LaravelDoctor\Repairs\StorageRepair::class,
        'config:cache' => \LaravelDoctor\Repairs\ConfigRepair::class,
        'cache:clear'  => \LaravelDoctor\Repairs\CacheRepair::class,
    ];

    public function handle(DoctorManager $manager): int
    {
        $this->info("Scanning application to locate repairable issues...");

        $doctor = $manager->getDoctor();
        $checks = config('doctor.checks', []);
        
        $results = [];
        foreach ($checks as $check) {
            $results[] = $doctor->runCheck($check);
        }

        $repairableResults = array_filter($results, function ($res) {
            return ($res->isFailed() || $res->isWarning()) && $res->isRepairAvailable();
        });

        if (empty($repairableResults)) {
            $this->info("No repairable issues detected. Your app is in good shape!");
            return 0;
        }

        $this->warn("Found " . count($repairableResults) . " issue(s) that can be automatically repaired.");
        $this->newLine();

        $repairedCount = 0;

        foreach ($repairableResults as $result) {
            $command = $result->getRepairCommand();
            $this->line("Issue: <comment>{$result->getMessage()}</comment>");
            $this->line("Recommendation: {$result->getRecommendation()}");

            if (!$this->option('force')) {
                if (!$this->confirm("Would you like to run repair action '{$command}'?", true)) {
                    $this->line("Skipped.");
                    $this->newLine();
                    continue;
                }
            }

            $this->comment("Running repair action '{$command}'...");

            $success = false;

            if (isset($this->repairsMap[$command])) {
                try {
                    $repairInstance = $this->laravel->make($this->repairsMap[$command]);
                    if ($repairInstance instanceof Repair) {
                        $success = $repairInstance->repair();
                    }
                } catch (Throwable $e) {
                    $this->error("Failed executing repair class: " . $e->getMessage());
                }
            } else {
                try {
                    $exitCode = $this->call($command);
                    $success = ($exitCode === 0);
                } catch (Throwable $e) {
                    $this->error("Failed running artisan command: " . $e->getMessage());
                }
            }

            if ($success) {
                $this->info("✔ Repair successfully executed!");
                $repairedCount++;
            } else {
                $this->error("✘ Repair execution failed.");
            }

            $this->newLine();
        }

        $this->info("Finished! Repaired {$repairedCount} of " . count($repairableResults) . " issues.");

        return 0;
    }
}
