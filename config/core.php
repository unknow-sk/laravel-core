<?php

declare(strict_types=1);

use UnknowSk\Core\Exceptions\Handler;
use UnknowSk\Core\Http\Kernel;

// config for UnknowSk/Core
return [
    'http' => [
        'kernel' => Kernel::class,
        'auth' => [
            'policies' => [],
        ],
    ],
    'console' => [
        'kernel' => \UnknowSk\Core\Console\Kernel::class,
        'disabled' => [
            'cache:table',
            'queue:table',
            'notifications:table',
            'session:table',
        ],
    ],
    'event' => [
        'listen' => [],
        'subscribe' => [],
        'model' => [
            'creating' => [],
            'created' => [],
            'updating' => [],
            'updated' => [],
            'deleting' => [],
            'deleted' => [],
        ],
    ],
    'exception' => [
        'handler' => Handler::class,
        'reportable' => [],
        'dont_flash' => [],
        'dont_report' => [],
        'levels' => [],
    ],
    'languages' => [
        'default' => env('APP_LOCALE', 'en'),
        'supported' => [
            //            'en' => 'English',
            //            'de' => 'Deutsch',
            //            'sk' => 'Slovenčina',
            //            'cz' => 'Čeština',
        ],
    ],
    /*
     * Default Formatter Formats
     */
    'formatter' => [
        'date' => env('FORMATTER_DATE', 'Y-m-d'),
        'datetime' => env('FORMATTER_DATETIME', 'Y-m-d H:i:s'),
        'time' => env('FORMATTER_TIME', 'H:i:s'),
    ],
];
