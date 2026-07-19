<?php

namespace LaravelDoctor\Checks;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use Illuminate\Contracts\Container\Container;

class MailCheck implements Check
{
    public function __construct(protected Container $container) {}

    public function check(): CheckResult
    {
        $app = $this->container->make('app');
        $config = $this->container->make('config');
        
        $driver = $config->get('mail.default') ?? $config->get('mail.driver');

        if ($app->environment('production') && in_array($driver, ['log', 'array', null])) {
            return CheckResult::warning(
                'Mail Driver',
                "Mail driver is set to '" . ($driver ?? 'null') . "' in production.",
                'Emails will not be sent to your users. Update mail configurations with a real provider (e.g. SMTP, SES, Postmark).',
                false,
                null,
                Severity::HIGH
            );
        }

        return CheckResult::success('Mail Driver', "Mail driver is configured to use '{$driver}'.");
    }
}
