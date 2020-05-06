<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Resources\SliceItemResource;
use App\Models\Slice;
use App\Models\SliceItem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliceItemsController extends Controller
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
        $slice_id = $request->slice_id;

        $paginationData = SliceItem::when($q, function($query, $q){
            $query->where('url', 'like', '%'.$q.'%');
        })->when($slice_id, function($query, $slice_id){
            $query->where('slice_id', $slice_id);
        })->orderBy('id','desc')->paginate($limit);
        $items = SliceItemResource::collection($paginationData);
        $items->appends($request->query());

        $allSlices = Slice::all();

        return view('admin.slices.items.index',[
            'items' => $items,
            'allSlices' => $allSlices
        ]);
    }

    /**
     * 添加
     * @return Application|Factory|View
     */
    public function store() {
        $allSlices = Slice::all();
        return view('admin.slices.items.store',[
            'allSlices' => $allSlices
        ]);
    }

    /**
     * 编辑
     * @param SliceItem $sliceItem
     * @return Application|Factory|View
     */
    public function profile(SliceItem $sliceItem) {
        $allSlices = Slice::all();
        return view('admin.slices.items.store', [
            'item' => $sliceItem,
            'allSlices' => $allSlices
        ]);
    }
}
