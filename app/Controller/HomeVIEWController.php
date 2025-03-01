<?php

namespace App\Controller;

use App\Http\Request;
use App\Models\Quote;

class HomeVIEWController extends Controller
{
    public function index(Request $request)
    {
        $quotes = Quote::findAllBy([
            'perPage' => 3,
        ]);

        $this->layout('top');
        $this->view('home', compact('quotes'));
        $this->layout('bottom');
    }
}
