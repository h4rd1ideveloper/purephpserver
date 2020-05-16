<?php

$container->set('db_settings', fn() => [
    'driver' => getenv('driver') ?? (defined('driver') ? driver : 'mysql'),
    'host' => getenv('host') ?? (defined('host') ? host : 'localhost'),
    'port' => getenv('port') ?? (defined('port') ? port : '3306'),
    'database' => getenv('database') ?? (defined('database') ? database : 'icardo_dev'),
    'username' => getenv('username') ?? (defined('username') ? username : 'root'),
    'password' => getenv('password') ?? (defined('password') ? password : ''),
    'charset' => getenv('charset') ?? (defined('charset') ? charset : 'utf8'),
    'collation' => getenv('collation') ?? (defined('collation') ? collation : 'utf8_unicode_ci'),
]);

