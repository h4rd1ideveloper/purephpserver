<?php

use App\lib\Helpers;

require(__DIR__ . '/vendor/autoload.php');

Helpers::setEnvByFile('.env');
return [
    'paths' => [
        'migrations' => 'src/database/migrations/',
        'seeds' => 'src/database/seeds/'
    ],
    'environments' => [
        'migration_base_class'=>'',
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => environments('driver', 'mysql'),
            'host' => environments('host', 'localhost'),
            'name' => environments('database', 'icardo_dev'),
            'user' => environments('username', 'root'),
            'pass' => environments('password', ''),
            'port' => environments('port', '3306')
        ]
    ]
];