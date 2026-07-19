<?php

namespace LaravelDoctor\Core;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use Illuminate\Contracts\Container\Container;
use Throwable;

class Doctor
{
    
    protected array $checks = [];

    public function __construct(protected Container $container) {}

    public function registerCheck(string|Check $check): self
    {
        $this->checks[] = $check;
        return $this;
    }

    public function registerChecks(array $checks): self
    {
        foreach ($checks as $check) {
            $this->registerCheck($check);
        }
        return $this;
    }

    public function getChecks(): array
    {
        return $this->checks;
    }

    public function run(): array
    {
        $results = [];

        foreach ($this->checks as $check) {
            $results[] = $this->runCheck($check);
        }

        return $results;
    }

    public function runCheck(string|Check $check): CheckResult
    {
        try {
            $instance = is_string($check) ? $this->container->make($check) : $check;

            if (!$instance instanceof Check) {
                $className = is_string($check) ? $check : get_class($check);
                return CheckResult::fail(
                    $className,
                    "Check class '{$className}' must implement " . Check::class,
                    'Make sure the check implements the check contract.',
                    false,
                    null,
                    Severity::CRITICAL
                );
            }

            return $instance->check();
        } catch (Throwable $e) {
            $name = is_string($check) ? $check : get_class($check);
            return CheckResult::fail(
                $name,
                "Check failed with exception: " . $e->getMessage(),
                "Fix the runtime error in check execution.",
                false,
                null,
                Severity::CRITICAL
            );
        }
    }
}
