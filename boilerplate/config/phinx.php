<?php
return [
    'paths' => [
        'migrations' => 'config/Migrations',
        'seeds' => 'config/Seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'default',
        'default' => [
            'adapter' => 'sqlite',
            'name' => __DIR__ . '/../database.sqlite',
        ]
    ]
];
