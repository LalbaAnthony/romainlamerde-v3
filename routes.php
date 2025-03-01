<?php
return [
    '/' => [
        'GET'  => 'View\\HomeController@index',
    ],
    '/404' => [
        'GET'  => 'View\\ErrorController@index',
    ],
    '/api/categories' => [
        'GET'  => 'API\\CategoryController@index',
    ],
];
