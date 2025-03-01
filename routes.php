<?php
return [
    '/' => [
        'GET'  => 'HomeVIEWController@index',
    ],
    '/404' => [
        'GET'  => 'ErrorVIEWController@index',
    ],
    '/api/categories' => [
        'GET'  => 'CategoryAPIController@index',
    ],
];
