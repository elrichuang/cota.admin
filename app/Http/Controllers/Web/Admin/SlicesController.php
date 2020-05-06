<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Resources\SliceResource;
use App\Models\Slice;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SlicesController extends Controller
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

        $paginationData = Slice::when($q, function($query, $q){
            $query->where('name', 'like', '%'.$q.'%');
        })->orderBy('id','desc')->paginate($limit);
        $items = SliceResource::collection($paginationData);
        $items->appends($request->query());

        return view('admin.slices.index',[
            'items' => $items
        ]);
    }

    /**
     * 添加
     * @return Application|Factory|View
     */
    public function store() {
        return view('admin.slices.store');
    }

    /**
     * 编辑
     * @param Slice $slice
     * @return Application|Factory|View
     */
    public function profile(Slice $slice) {
        return view('admin.slices.store', [
            'item' => $slice,
        ]);
    }
}
