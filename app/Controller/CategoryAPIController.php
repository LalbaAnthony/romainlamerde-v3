<?php

namespace App\Controller;

use App\Http\Request;
use App\Models\Category;


class CategoryAPIController extends Controller
{
    public function index(Request $request)
    {
        $search = (string) (isset($request->params['search']) ? $request->params['search'] : '');
        $perPage = (int) (isset($request->params['perPage']) ? $request->params['perPage'] : parent::DEFAULT_PER_PAGE);
        $page = (int) (isset($request->params['page']) ? $request->params['page'] : parent::DEFAULT_PAGE);
        $sort = (array) (isset($request->params['sort']) ? $request->params['sort'] : parent::DEFAULT_SORT);

        $categories = Category::findAllBy([
            'search' => $search,
            'perPage' => $perPage,
            'page' => $page,
            'sort' => $sort,
        ]);

        $this->response(200, $categories);
    }
}
