<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Resources\UserLogResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web',[
            'except'=>['login']
        ]);

        //$this->authorizeResource(User::class,'user');
    }

    /**
     * 管理员列表
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request) {
        $limit = config('admin.page_limit');

        $q = $request->q;

        $paginationData = User::when($q, function($query, $q){
            $query->where('name', 'like', '%'.$q.'%')->orWhere('email', 'like', '%'.$q.'%');
        })->orderBy('id','desc')->paginate($limit);
        $items = UserResource::collection($paginationData);
        $items->appends($request->query());
        return view('admin.users.index',[
            'items' => $items
        ]);
    }

    /**
     * 后台登录页面
     * @return Application|Factory|\Illuminate\Http\RedirectResponse|View
     */
    public function login() {
        if (auth('admin_web')->check()) {
            return response()->redirectToRoute('admin.dashboard');
        }
        return view('admin.users.login');
    }

    /**
     * 添加管理员
     * @return Application|Factory|View
     */
    public function store() {
        $roles = Role::all();
        return view('admin.users.store',[
            'roles' => $roles
        ]);
    }

    /**
     * 编辑管理员
     * @param User $user
     * @return Application|Factory|View
     */
    public function profile(User $user) {
        $roles = Role::all();
        return view('admin.users.store', [
            'roles' => $roles,
            'user' => $user
        ]);
    }

    /**
     * 管理员日志页面
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function log(Request $request)
    {
        $user_id = $request->get('user_id');
        $limit = config('admin.page_limit');
        $q = $request->q;

        $paginationData = UserLog::when($q, function($query, $q){
            $query->where('request_data', 'like', '%'.$q.'%')
                ->orWhere('response_data', 'like', '%'.$q.'%')
                ->orWhere('uri', 'like', '%'.$q.'%');
        })->when($user_id, function ($query, $user_id) {
            return $query->where('user_id',$user_id);
        })->orderBy('id','desc')->paginate($limit);
        $items = UserLogResource::collection($paginationData);

        // 所有管理员
        $users = User::all();

        return view('admin.users.log',[
            'items' => $items,
            'users' => $users
        ]);
    }
}
