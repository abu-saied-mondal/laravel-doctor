<?php

namespace LaravelDoctor;

use Illuminate\Support\ServiceProvider;
use LaravelDoctor\Core\Doctor;
use LaravelDoctor\Core\DoctorManager;
use LaravelDoctor\Commands\DoctorCommand;
use LaravelDoctor\Commands\ScanCommand;
use LaravelDoctor\Commands\RepairCommand;
use LaravelDoctor\Commands\ProductionCommand;

class LaravelDoctorServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/doctor.php', 'doctor'
        );

        $this->app->singleton(Doctor::class, function ($app) {
            return new Doctor($app);
        });

        $this->app->singleton(DoctorManager::class, function ($app) {
            return new DoctorManager($app, $app->make(Doctor::class));
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Config/doctor.php' => config_path('doctor.php'),
            ], 'doctor-config');

            $this->commands([
                DoctorCommand::class,
                ScanCommand::class,
                RepairCommand::class,
                ProductionCommand::class,
            ]);
        }
    }
}
