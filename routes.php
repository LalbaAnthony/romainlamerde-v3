<?php
return [
    '/' => [
        'GET'  => 'ExampleController@home',
    ],
    '/api/example' => [
        'POST' => 'ExampleController@create',
        'GET'  => 'ExampleController@read',
    ],
    '/api/categories' => [
            'GET'  => 'CategoryAPIController@index',
    ],
];
