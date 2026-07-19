<?php

return [
    
    'checks' => [
        LaravelDoctor\Checks\AppKeyCheck::class,
        LaravelDoctor\Checks\StorageCheck::class,
        LaravelDoctor\Checks\PermissionCheck::class,
        LaravelDoctor\Checks\DatabaseCheck::class,
        LaravelDoctor\Checks\ConfigCheck::class,
        LaravelDoctor\Checks\CacheCheck::class,
        LaravelDoctor\Checks\QueueCheck::class,
        LaravelDoctor\Checks\MailCheck::class,
    ],

    'production_checks' => [
        LaravelDoctor\Checks\AppKeyCheck::class,
        LaravelDoctor\Checks\PermissionCheck::class,
        LaravelDoctor\Checks\DatabaseCheck::class,
        LaravelDoctor\Checks\ConfigCheck::class,
        LaravelDoctor\Checks\CacheCheck::class,
        LaravelDoctor\Checks\QueueCheck::class,
        LaravelDoctor\Checks\MailCheck::class,
    ],

    'performance_checks' => [
        LaravelDoctor\Checks\ConfigCheck::class,
        LaravelDoctor\Checks\CacheCheck::class,
        LaravelDoctor\Checks\QueueCheck::class,
    ],
];
