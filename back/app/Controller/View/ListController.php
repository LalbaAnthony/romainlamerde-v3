<?php

namespace App\Controller\View;

use App\Http\Request;
use App\Models\Quote;
use App\Controller\Controller;

class ListController extends Controller
{
    public function index(Request $request)
    {
        $quotes = Quote::findAll();

        $this->view('list', compact('quotes'));
    }
}
