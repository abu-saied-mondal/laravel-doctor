<?php

namespace LaravelDoctor\Checks;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use Illuminate\Contracts\Container\Container;

class AppKeyCheck implements Check
{
    public function __construct(protected Container $container) {}

    public function check(): CheckResult
    {
        $config = $this->container->make('config');
        $key = $config->get('app.key');

        if (empty($key)) {
            return CheckResult::fail(
                'App Key',
                'APP_KEY is missing or empty.',
                'Run "php artisan key:generate" to generate a secure application key.',
                true,
                'key:generate',
                Severity::CRITICAL
            );
        }

        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7), true);
        }

        if ($key === false || strlen($key) === 0) {
            return CheckResult::fail(
                'App Key',
                'APP_KEY is present but invalid.',
                'Run "php artisan key:generate" to regenerate the key.',
                true,
                'key:generate',
                Severity::CRITICAL
            );
        }

        return CheckResult::success('App Key', 'APP_KEY is set and valid.');
    }
}
