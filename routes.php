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
    '/list' => [
        'GET'  => [
            'type' => 'view',
            'path' => 'View\\ListController@index',
            'hooks' => [
                'before' => ['components' => ['header']],
                'after' => ['components' => ['footer']],
            ]
        ],
    ],
    // =================================================
    // API
    // =================================================
    '/api/categories' => [
        'GET'  => [
            'type' => 'api',
            'path' => 'API\\CategoryController@index',
        ],
    ],
    // =================================================
    // Error
    // =================================================
    '/404' => [
        'GET'  => [
            'type' => 'view',
            'path' => 'View\\ErrorController@notFound',
        ],
    ],
    '/500' => [
        'GET'  => [
            'type' => 'view',
            'path' => 'View\\ErrorController@serverError',
        ],
    ],
];
