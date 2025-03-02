<?php

namespace App\Controller\View;

use App\Http\Request;
use App\Controller\Controller;

class ErrorController  extends Controller
{
    public function index(Request $request)
    {
        $this->view('404');
    }
}
