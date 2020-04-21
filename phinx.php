<?php

return [
    'paths' => [
        'migrations' => 'src/database/migrations/',
        'seeds' => 'src/database/seeds/'
    ],
    'migration_base_class' => '\App\database\migrations\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'icardo_dev',
            'user' => 'root',
            'pass' => '',
            'port' => '3306'
        ]
    ]
];