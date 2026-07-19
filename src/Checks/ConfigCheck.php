<?php

namespace LaravelDoctor\Checks;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use Illuminate\Contracts\Container\Container;

class ConfigCheck implements Check
{
    public function __construct(protected Container $container) {}

    public function check(): CheckResult
    {
        $app = $this->container->make('app');
        $isCached = $app->configurationIsCached();

        if (!$isCached) {
            $isProduction = $app->environment('production');
            $severity = $isProduction ? Severity::HIGH : Severity::LOW;
            $rec = $isProduction 
                ? 'Configuration must be cached in production. Run "php artisan config:cache".' 
                : 'Consider running "php artisan config:cache" in production to improve boot times.';

            return CheckResult::warning(
                'Configuration Cache',
                'Application configurations are not cached.',
                $rec,
                true,
                'config:cache',
                $severity
            );
        }

        return CheckResult::success('Configuration Cache', 'Application configurations are cached.');
    }
}
