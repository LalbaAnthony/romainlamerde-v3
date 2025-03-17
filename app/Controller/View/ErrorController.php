<?php

namespace App\Controller\View;

use App\Http\Request;
use App\Controller\Controller;

class ErrorController  extends Controller
{
    public function notFound(Request $request)
    {
        $this->view('404');
    }

    public function serverError(Request $request)
    {
        $this->view('500');
    }
}
