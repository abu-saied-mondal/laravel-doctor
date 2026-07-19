<?php

namespace LaravelDoctor\Checks;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use Illuminate\Contracts\Container\Container;

class QueueCheck implements Check
{
    public function __construct(protected Container $container) {}

    public function check(): CheckResult
    {
        $app = $this->container->make('app');
        $config = $this->container->make('config');
        $driver = $config->get('queue.default');

        if ($app->environment('production') && $driver === 'sync') {
            return CheckResult::warning(
                'Queue Driver',
                'Queue driver is set to "sync" in production.',
                'Background jobs (like sending mail) will run synchronously, degrading user response times. Consider changing queue driver to "database" or "redis".',
                false,
                null,
                Severity::HIGH
            );
        }

        return CheckResult::success('Queue Driver', "Queue is configured to use '{$driver}' driver.");
    }
}
