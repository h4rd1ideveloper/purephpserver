<?php


namespace App\lib;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class Factory
 * @package App\lib
 */
class Factory
{
    const defaultFileToLog = '/monolog.log';

    /**
     * @param string $name
     * @param array $options = [logPath,level]
     * @return Logger
     */
    public static function logger(string $name, array $options = []): Logger
    {
        $options[0] |= Logger::ALERT;
        $options[1] |= self::defaultFileToLog;
        [$file, $level] = $options;
        $log = new Logger($name);
        $log->pushHandler(new StreamHandler($file, $level));
        return $log;
    }
}