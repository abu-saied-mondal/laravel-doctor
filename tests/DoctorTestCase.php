<?php

namespace LaravelDoctor\Tests;

use Orchestra\Testbench\TestCase;
use LaravelDoctor\LaravelDoctorServiceProvider;

class DoctorTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelDoctorServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        
        $app['config']->set('doctor.checks', [
            \LaravelDoctor\Checks\AppKeyCheck::class,
            \LaravelDoctor\Checks\StorageCheck::class,
        ]);
    }
}
