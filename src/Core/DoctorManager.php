<?php

namespace LaravelDoctor\Core;

use Illuminate\Contracts\Container\Container;
use LaravelDoctor\Support\CheckResult;

class DoctorManager
{
    public function __construct(
        protected Container $container,
        protected Doctor $doctor
    ) {}

    public function getDoctor(): Doctor
    {
        return $this->doctor;
    }

    public function runDefaultChecks(): array
    {
        $checks = $this->container->make('config')->get('doctor.checks', []);
        
        $this->doctor->registerChecks($checks);

        return $this->doctor->run();
    }

    public function runChecksFor(string $type): array
    {
        $checks = $this->container->make('config')->get("doctor.{$type}_checks", []);

        $doctor = new Doctor($this->container);
        $doctor->registerChecks($checks);

        return $doctor->run();
    }
}
