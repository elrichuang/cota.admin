<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Ability;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AbilitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');

        //$this->authorizeResource(User::class,'user');
    }

    /**
     * 管理员列表
     * @return Application|Factory|View
     */
    public function index() {
        $items = Ability::getTreeData();
        return view('admin.abilities.index',[
            'items' => $items
        ]);
    }

    /**
     * 添加
     * @return Application|Factory|View
     */
    public function store() {
        $allAbilities = Ability::getTreeData();

        $allRoutes = Route::getRoutes();

        return view('admin.abilities.store',[
            'allAbilities' => $allAbilities,
            'allRoutes' => $allRoutes
        ]);
    }

    /**
     * 编辑
     * @param Request $request
     * @return Application|Factory|View
     */
    public function profile(Request $request) {
        if (!$request->id) {
            abort(404);
        }

        $ability = Ability::where(['id'=>$request->id])->first();
        if (!$ability) {
            abort(404);
        }

        $allAbilities = Ability::getTreeData();

        $allRoutes = Route::getRoutes();

        return view('admin.abilities.store', [
            'item'=>$ability,
            'allAbilities' => $allAbilities,
            'allRoutes' => $allRoutes
        ]);
    }
}
