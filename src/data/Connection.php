<?php

namespace App\infra\database;


use App\infra\lib\Factory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Builder;
use RuntimeException;

class Connection
{
    private static ?Connection $instance = null;
    public Builder $schema;
    public Capsule $capsule;

    /**
     * is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead
     */
    private function __construct()
    {
        [$capsule, $schema] = Factory::illuminateDatabase([
            'driver' => 'mysql',
            'host' => environments('DB_HOST'),
            'port' => environments('DB_PORT'),
            'database' => environments('DB_NAME'),
            'username' => environments('DB_USER'),
            'password' => environments('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);
        $this->capsule = $capsule;
        $this->schema = $schema;
    }

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Connection
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * prevent from being un-serialized (which would create a second instance of it)
     */
    public function __wakeup()
    {
        throw new RuntimeException("Cannot unserialize singleton");
    }

    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone()
    {
    }
}


