<?php

namespace App\Controller\API;

use App\Http\Request;
use App\Models\Category;
use App\Controller\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = (string) (isset($request->params['search']) ? $request->params['search'] : '');
        $perPage = (int) (isset($request->params['perPage']) ? $request->params['perPage'] : parent::DEFAULT_PER_PAGE);
        $page = (int) (isset($request->params['page']) ? $request->params['page'] : parent::DEFAULT_PAGE);
        $sort = (array) (isset($request->params['sort']) ? $request->params['sort'] : parent::DEFAULT_SORT);

        $return = Category::findAllBy([
            'search' => $search,
            'perPage' => $perPage,
            'page' => $page,
            'sort' => $sort,
        ]);

        $status = count((array) $return['data']) > 0 ? 200 : 204;

        $this->response($status, $return);
    }
}
