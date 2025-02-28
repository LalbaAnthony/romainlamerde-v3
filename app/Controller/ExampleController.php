<?php

namespace App\Controller;

use App\Http\Request;

class ExampleController extends Controller
{
    public function read(Request $request)
    {
        $data = [
            'id' => 1,
            'name' => 'Exemple',
            'description' => 'Ceci est un exemple de réponse API'
        ];

        $this->response(200, $data);
    }

    public function create(Request $request)
    {
        $input = $request->getJson();

        if (!isset($input['name'])) {
            $this->response(400, ['error' => 'Le champ "name" est requis']);
        }

        $createdData = [
            'id' => 2,
            'name' => $input['name'],
            'description' => $input['description'] ?? 'Aucune description'
        ];
        $this->response(201, $createdData);
    }

    public function home(Request $request)
    {
        $this->view('home');
    }
}
