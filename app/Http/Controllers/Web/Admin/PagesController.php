<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');

        //$this->authorizeResource(User::class,'user');
    }

    /**
     * 列表
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request) {
        $limit = config('admin.page_limit');

        $q = $request->q;

        $paginationData = Page::when($q, function($query, $q){
            $query->where('title', 'like', '%'.$q.'%')
                ->orWhere('content', 'like', '%'.$q.'%');
        })->orderBy('id','desc')->paginate($limit);
        $items = PageResource::collection($paginationData);
        $items->appends($request->query());

        return view('admin.pages.index',[
            'items' => $items
        ]);
    }

    /**
     * 添加
     * @return Application|Factory|View
     */
    public function store() {
        return view('admin.pages.store',[
        ]);
    }

    /**
     * 编辑
     * @param Page $page
     * @return Application|Factory|View
     */
    public function profile(Page $page) {
        return view('admin.pages.store', [
            'item' => $page,
        ]);
    }
}
