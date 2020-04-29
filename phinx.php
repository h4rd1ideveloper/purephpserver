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
            'adapter' =>  'mysql',
            'host' =>  'localhost',
            'name' =>  'icardo_dev',
            'user' =>  'root',
            'pass' =>  '',
            'port' =>  '3306'
        ]
    ]
];