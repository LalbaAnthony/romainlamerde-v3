<?php

return [
    // =================================================
    // Views
    // =================================================
    '/' => [
        'GET'  => [
            'type' => 'view',
            'path' => 'View\\HomeController@index',
            'hooks' => [
                'before' => ['components' => ['header']],
                'after' => ['components' => ['footer']],
            ]
        ],
    ],
    '/404' => [
        'GET'  => [
            'type' => 'view',
            'path' => 'View\\ErrorController@index',
            'hooks' => [
                'after' => ['components' => ['footer']],
            ]
        ],
    ],
    // =================================================
    // API
    // =================================================
    '/api/categories' => [
        'GET'  => [
            'path' => 'API\\CategoryController@index',
        ],
    ],
];
