<?php

namespace LaravelDoctor\Commands;

use Illuminate\Console\Command;
use LaravelDoctor\Core\DoctorManager;
use LaravelDoctor\Console\TablePrinter;
use LaravelDoctor\Console\ProgressPrinter;
use LaravelDoctor\Support\HealthScore;

class ScanCommand extends Command
{
    
    protected $signature = 'doctor:scan {--type=default : The category of checks to run}';

    protected $description = 'Scan application for health issues';

    public function handle(DoctorManager $manager): int
    {
        $type = $this->option('type');
        $doctor = $manager->getDoctor();

        if ($type === 'default') {
            $checks = config('doctor.checks', []);
        } else {
            $checks = config("doctor.{$type}_checks", []);
        }

        if (empty($checks)) {
            $this->warn("No checks found for type '{$type}'.");
            return 0;
        }

        $printer = new ProgressPrinter($this);
        $printer->start(count($checks));

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
        $this->line("Overall Health Score: <{$scoreColor}>{$score}/100</{$scoreColor}>");
        $this->newLine();

        $hasFailures = false;
        foreach ($results as $res) {
            if ($res->isFailed()) {
                $hasFailures = true;
                break;
            }
        }

        return $hasFailures ? 1 : 0;
    }
}
