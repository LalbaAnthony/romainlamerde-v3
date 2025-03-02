<?php

namespace App\Controller\View;

use App\Http\Request;
use App\Models\Quote;
use App\Controller\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $quotes = Quote::findAllBy([
            'perPage' => 3,
        ]);

        $this->view('home', compact('quotes'));
    }
}
