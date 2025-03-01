<?php

namespace App\Controller;

abstract class Controller
{
    use \App\Http\Utils;

    const DEFAULT_PER_PAGE = 10;
    const DEFAULT_PAGE = 1;
    const DEFAULT_SORT = [['column' => 'created_at', 'order' => 'DESC']];
}
