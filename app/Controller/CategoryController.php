<?php

namespace App\Controller;

use App\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    const DEFAULT_PER_PAGE = 10;
    const DEFAULT_PAGE = 1;
    const DEFAULT_SORT = [['column' => 'created_at', 'order' => 'DESC']];

    public function index(Request $request)
    {
        $search = (string) (isset($request->params['search']) ? $request->params['search'] : '');
        $perPage = (int) (isset($request->params['perPage']) ? $request->params['perPage'] : self::DEFAULT_PER_PAGE);
        $page = (int) (isset($request->params['page']) ? $request->params['page'] : self::DEFAULT_PAGE);
        $sort = (array) (isset($request->params['sort']) ? $request->params['sort'] : self::DEFAULT_SORT);

        $data = Category::findAllBy([
            'search' => $search,
            'perPage' => $perPage,
            'page' => $page,
            'sort' => $sort,
        ]);

        $this->response(200, $data);
    }
}
