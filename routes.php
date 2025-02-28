<?php
return [
    '/home' => [
        'GET'  => 'ExampleController@home',
    ],
    '/api/example' => [
        'POST' => 'ExampleController@create',
        'GET'  => 'ExampleController@read',
    ],
];
