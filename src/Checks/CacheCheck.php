<?php

namespace LaravelDoctor\Checks;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use Illuminate\Contracts\Container\Container;
use Throwable;

class CacheCheck implements Check
{
    public function __construct(protected Container $container) {}

    public function check(): CheckResult
    {
        $app = $this->container->make('app');
        $config = $this->container->make('config');
        
        if (!$this->container->bound('cache')) {
            return CheckResult::fail(
                'Cache Store',
                'Cache service is not bound to the container.',
                'Check config/app.php providers for Illuminate\Cache\CacheServiceProvider.',
                false,
                null,
                Severity::CRITICAL
            );
        }

        $cache = $this->container->make('cache');
        $driver = $config->get('cache.default');

        if ($app->environment('production') && $driver === 'file') {
            return CheckResult::warning(
                'Cache Driver',
                'Default cache driver is set to "file" in production.',
                'For better performance and scalability, consider using "redis" or "memcached".',
                false,
                null,
                Severity::MEDIUM
            );
        }

        try {
            $testKey = 'laravel_doctor_cache_test_' . time();
            $cache->put($testKey, 'ok', 10);
            $value = $cache->get($testKey);
            $cache->forget($testKey);

            if ($value !== 'ok') {
                return CheckResult::fail(
                    'Cache Store',
                    "Cache test failed. Wrote 'ok' but got back a different value.",
                    'Verify that the cache store is running and configured correctly.',
                    false,
                    null,
                    Severity::HIGH
                );
            }
        } catch (Throwable $e) {
            return CheckResult::fail(
                'Cache Store',
                "Cache connection failed: {$e->getMessage()}",
                "Check cache configuration and ensure driver '{$driver}' is running.",
                false,
                null,
                Severity::HIGH
            );
        }

        return CheckResult::success('Cache Store', "Cache store is operational using driver '{$driver}'.");
    }
}
