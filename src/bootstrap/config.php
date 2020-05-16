<?php

$container->set('db_settings', fn() => [
    'driver' => getenv('driver') ?? 'mysql',
    'host' => getenv('host') ?? 'localhost',
    'port' => getenv('port') ?? '3306',
    'database' => getenv('database') ?? 'icardo_dev',
    'username' => getenv('username') ?? 'root',
    'password' => getenv('password') ?? '',
    'charset' => getenv('charset') ?? 'utf8',
    'collation' => getenv('collation') ?? 'utf8_unicode_ci',
]);

