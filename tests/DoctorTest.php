<?php

namespace LaravelDoctor\Tests;

use LaravelDoctor\Core\Doctor;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use LaravelDoctor\Support\HealthScore;
use LaravelDoctor\Checks\AppKeyCheck;
use LaravelDoctor\Checks\StorageCheck;

class DoctorTest extends DoctorTestCase
{
    public function testItCanCalculateHealthScore()
    {
        $results = [
            CheckResult::success('Check 1', 'Passed'),
            CheckResult::success('Check 2', 'Passed'),
        ];
        $this->assertEquals(100, HealthScore::calculate($results));

        $resultsWithWarning = [
            CheckResult::success('Check 1', 'Passed'),
            CheckResult::warning('Check 2', 'Warning', null, false, null, Severity::MEDIUM),
        ];
        $this->assertEquals(95, HealthScore::calculate($resultsWithWarning));

        $resultsWithFailure = [
            CheckResult::success('Check 1', 'Passed'),
            CheckResult::fail('Check 2', 'Failed', null, false, null, Severity::HIGH),
        ];
        $this->assertEquals(80, HealthScore::calculate($resultsWithFailure));

        $resultsWithCritical = [
            CheckResult::success('Check 1', 'Passed'),
            CheckResult::fail('Check 2', 'Failed', null, false, null, Severity::CRITICAL),
        ];
        $this->assertEquals(70, HealthScore::calculate($resultsWithCritical));
    }

    public function testAppKeyCheckDetectsMissingKey()
    {
        $this->app['config']->set('app.key', '');

        $check = new AppKeyCheck($this->app);
        $result = $check->check();

        $this->assertTrue($result->isFailed());
        $this->assertEquals('APP_KEY is missing or empty.', $result->getMessage());
        $this->assertEquals(Severity::CRITICAL, $result->getSeverity());
    }

    public function testAppKeyCheckPassesWhenKeyExists()
    {
        $this->app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));

        $check = new AppKeyCheck($this->app);
        $result = $check->check();

        $this->assertTrue($result->isPassed());
        $this->assertEquals('APP_KEY is set and valid.', $result->getMessage());
    }

    public function testStorageCheckDetectsMissingSymlink()
    {
        $this->app->bind('path.public', function () {
            return __DIR__ . '/fake_public';
        });

        $check = new StorageCheck($this->app);
        $result = $check->check();

        $this->assertTrue($result->isFailed());
        $this->assertEquals('Public storage symlink is missing.', $result->getMessage());
    }

    public function testItCanRunScanArtisanCommand()
    {
        $this->app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        
        $this->artisan('doctor:scan')
            ->assertExitCode(1); 
    }
}
