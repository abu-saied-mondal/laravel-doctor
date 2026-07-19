<?php

namespace LaravelDoctor\Checks;

use LaravelDoctor\Contracts\Check;
use LaravelDoctor\Support\CheckResult;
use LaravelDoctor\Support\Severity;
use Illuminate\Contracts\Container\Container;

class StorageCheck implements Check
{
    public function __construct(protected Container $container) {}

    public function check(): CheckResult
    {
        $publicPath = $this->container->bound('path.public') 
            ? $this->container->make('path.public') 
            : base_path('public');

        $publicStoragePath = rtrim($publicPath, '/') . '/storage';

        if (!file_exists($publicStoragePath)) {
            return CheckResult::fail(
                'Storage Link',
                'Public storage symlink is missing.',
                'Run "php artisan storage:link" to create the symbolic link.',
                true,
                'storage:link',
                Severity::HIGH
            );
        }

        if (!is_link($publicStoragePath)) {
            return CheckResult::warning(
                'Storage Link',
                'The storage path exists but is not a symbolic link.',
                'Delete the existing directory/file and run "php artisan storage:link".',
                true,
                'storage:link',
                Severity::HIGH
            );
        }

        return CheckResult::success('Storage Link', 'Public storage symlink is linked correctly.');
    }
}
