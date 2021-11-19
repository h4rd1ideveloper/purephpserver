<?php


namespace App\lib;

use Monolog\Logger as MonoLogger;

/**
 * Class Logger
 * @package App\lib
 */
class Logger
{
    /**
     * @param string $message
     * @param string $logName
     * @param array $payload
     * @param int $level
     */
    public static function errorLog(string $message, string $logName, $payload = [], $level = MonoLogger::ERROR): void
    {
        $log = Factory::logger($logName, [$level]);
        $log->error($message, $payload ?? []);
    }

    /**
     * @param string $message
     * @param string $logName
     * @param array $payload
     * @param int $level
     */
    public static function alertLog(string $message, string $logName, $payload = [], $level = MonoLogger::ALERT): void
    {
        $log = Factory::logger($logName, [$level]);
        $log->alert($message, $payload ?? []);
    }

    /**
     * @param string $message
     * @param string $logName
     * @param array $payload
     * @param int $level
     */
    public static function debugLog(string $message, string $logName, $payload = [], $level = MonoLogger::DEBUG): void
    {
        $log = Factory::logger($logName, [$level]);
        $log->debug($message, $payload ?? []);
    }
}