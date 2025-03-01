<?php

namespace App\Controller;

use App\Http\Request;

class ErrorVIEWController  extends Controller
{
    public function index(Request $request)
    {
        $this->layout('top');
        $this->view('404');
        $this->layout('bottom');
    }
}
