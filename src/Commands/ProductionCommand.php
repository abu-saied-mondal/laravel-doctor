<?php

namespace LaravelDoctor\Commands;

use Illuminate\Console\Command;
use LaravelDoctor\Core\DoctorManager;
use LaravelDoctor\Console\TablePrinter;
use LaravelDoctor\Console\ProgressPrinter;
use LaravelDoctor\Support\HealthScore;

class ProductionCommand extends Command
{
    
    protected $signature = 'doctor:production';

    protected $description = 'Validate environment and configuration for production deployments';

    public function handle(DoctorManager $manager): int
    {
        $this->info("Verifying production environment readiness...");
        
        $checks = config('doctor.production_checks', []);
        
        if (empty($checks)) {
            $this->warn("No production checks registered.");
            return 0;
        }

        $printer = new ProgressPrinter($this);
        $printer->start(count($checks));

        $doctor = $manager->getDoctor();
        $results = [];
        
        foreach ($checks as $check) {
            $checkName = is_string($check) ? class_basename($check) : class_basename(get_class($check));
            if (str_ends_with($checkName, 'Check')) {
                $checkName = substr($checkName, 0, -5);
            }
            $printer->advance($checkName);
            $results[] = $doctor->runCheck($check);
        }

        $printer->finish();

        TablePrinter::print($this, $results);

        $score = HealthScore::calculate($results);
        $this->newLine();
        
        $scoreColor = $score >= 90 ? 'info' : ($score >= 60 ? 'comment' : 'error');
        $this->line("Production Readiness Score: <{$scoreColor}>{$score}/100</{$scoreColor}>");
        $this->newLine();

        $hasFailures = false;
        foreach ($results as $res) {
            if ($res->isFailed()) {
                $hasFailures = true;
                break;
            }
        }

        if ($hasFailures) {
            $this->error("✘ CRITICAL: One or more checks failed. Production deployment is unsafe!");
            return 1;
        }

        $this->info("✔ SUCCESS: All production verification checks passed.");
        return 0;
    }
}
