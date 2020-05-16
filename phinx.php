<?php

use App\lib\Helpers;

require(dirname(__FILE__) . '/vendor/autoload.php');

Helpers::setEnvByFile('.env');
return [
    'paths' => [
        'migrations' => 'src/database/migrations/',
        'seeds' => 'src/database/seeds/'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => getenv('driver') ?? (defined('driver') ? driver : 'mysql'),
            'host' => getenv('host') ?? (defined('host') ? host : 'localhost'),
            'name' => getenv('database') ?? (defined('database') ? database : 'icardo_dev'),
            'user' => getenv('username') ?? (defined('username') ? username : 'root'),
            'pass' => getenv('password') ?? (defined('password') ? password : ''),
            'port' => getenv('port') ?? (defined('port') ? port : '3306')
        ]
    ]
];