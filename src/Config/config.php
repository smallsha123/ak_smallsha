<?php


return [

    'mysql' => [


    ],

    'redis' => [

    ],


    'components' => [
        //数据库操作
//        'Medoo' => [
//            'class' => 'Smallsha/Classes/Medoo'
//        ],
//
        'Pay' => [
            'class' => 'Smallsha\Classes\Pay'
        ],
        'Page' => [
            'class' => 'Smallsha\Classes\Page'
        ]
    ],


];