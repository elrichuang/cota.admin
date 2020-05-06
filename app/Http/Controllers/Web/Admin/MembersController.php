<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembersController extends Controller
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

        $paginationData = Member::when($q, function($query, $q){
            $query->where('name', 'like', '%'.$q.'%')
                ->orWhere('nickname', 'like', '%'.$q.'%')
                ->orWhere('phone', 'like', '%'.$q.'%');
        })->orderBy('id','desc')->paginate($limit);
        $items = MemberResource::collection($paginationData);
        $items->appends($request->query());

        return view('admin.members.index',[
            'items' => $items
        ]);
    }

    /**
     * 添加
     * @return Application|Factory|View
     */
    public function store() {
        return view('admin.members.store',[
        ]);
    }

    /**
     * 编辑
     * @param Member $member
     * @return Application|Factory|View
     */
    public function profile(Member $member) {
        return view('admin.members.store', [
            'item' => $member,
        ]);
    }
}
