<?php


return [

    'database' => [
        'database_type' => 'mysql',
        'database_name' => 'name',
        'server' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8'
    ],

    'redis' => [

    ],


    'components' => [
        //数据库操作
        'Medoo' => [
            'class' => 'Smallsha/Classes/Medoo'
        ],
        'Pay' => [
            'class' => 'Smallsha\Classes\Pay'
        ],
        'Page' => [
            'class' => 'Smallsha\Classes\Page'
        ],
        'Medoo' => [
            'class' => 'Smallsha\Databases\Medoo',
            'constructor'=> [
                'database_type' => 'mysql',
                'database_name' => 'bags',
                'server' => '127.0.0.1',
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8'
            ]
        ],
    ],


];