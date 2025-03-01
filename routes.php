<?php
return [
    '/' => [
        'GET'  => 'HomeVIEWController@index',
    ],
    '/api/categories' => [
        'GET'  => 'CategoryAPIController@index',
    ],
];
