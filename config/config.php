<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;

date_default_timezone_set(env('TIMEZONE', 'Asia/Shanghai'));
echo date('Y-m-d H:i:s') . PHP_EOL;

return [
    'app_name'                   => env('APP_NAME', 'skeleton'),
    // 生产环境使用 prod 值
    'app_env'                    => env('APP_ENV', 'dev'),
    // 是否使用注解扫描缓存
    'scan_cacheable'             => env('SCAN_CACHEABLE', false),
    // 应用地址
    'app_host'                   => env('HOST', ''),
    StdoutLoggerInterface::class => [
        'log_level' => [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            // LogLevel::DEBUG,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            // LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
        ],
    ],
];
