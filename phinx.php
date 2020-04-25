<?php

use App\lib\Helpers;

require(dirname(__FILE__) . './vendor/autoload.php');

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
            'adapter' => getenv('driver') ?? 'mysql',
            'host' => getenv('host') ?? 'localhost',
            'name' => getenv('database') ?? 'icardo_dev',
            'user' => getenv('username') ?? 'root',
            'pass' => getenv('password') ?? '',
            'port' => getenv('port') ?? '3306'
        ]
    ]
];