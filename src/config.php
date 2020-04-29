<?php

    $container->set('db_settings', fn() => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'icardo_dev',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
    ]);

