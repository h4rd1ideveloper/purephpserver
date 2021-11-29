<?php


namespace App\infra\service\security;

use App\infra\lib\Factory;
use Monolog\Logger as MonoLogger;

/**
 * Class Logger
 * @package App\lib
 */
class Logger
{

    public static function errorLog(string $message, string $logName, array $payload = [], int $level = MonoLogger::ERROR): void
    {
        $log = Factory::logger($logName, [$level]);
        $log->error($message, $payload ?? []);
    }

    public static function alertLog(string $message, string $logName, array $payload = [], int $level = MonoLogger::ALERT): void
    {
        $log = Factory::logger($logName, [$level]);
        $log->alert($message, $payload ?? []);
    }

    public static function debugLog(string $message, string $logName, array $payload = [], int $level = MonoLogger::DEBUG): void
    {
        $log = Factory::logger($logName, [$level]);
        $log->debug($message, $payload ?? []);
    }
}