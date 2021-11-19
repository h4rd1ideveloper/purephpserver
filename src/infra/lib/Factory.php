<?php


namespace App\infra\lib;


use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class Factory
 * @package App\lib
 */
class Factory
{
    /**
     *
     */
    public const defaultFileToLog = '/monolog.log';

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

    /**
     * @param array $connectionConfig
     * @param string $connectionName
     * @return Capsule
     */
    public static function illuminateDatabase(array $connectionConfig, string $connectionName = 'default'): Capsule
    {
        $capsule = new Capsule;
        $capsule->addConnection($connectionConfig, $connectionName);
        $capsule->bootEloquent();
        $capsule->setAsGlobal();
        $capsule->schema();
        return $capsule;
    }
}