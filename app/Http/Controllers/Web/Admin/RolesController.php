<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Resources\RoleResource;
use App\Models\Ability;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RolesController extends Controller
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

        $paginationData = Role::when($q, function($query, $q){
            $query->where('name', 'like', '%'.$q.'%');
        })->orderBy('id','desc')->paginate($limit);
        $items = RoleResource::collection($paginationData);
        $items->appends($request->query());

        return view('admin.roles.index',[
            'items' => $items
        ]);
    }

    /**
     * 添加
     * @return Application|Factory|View
     */
    public function store() {
        $allAbilities = Ability::getTreeData();
        return view('admin.roles.store',[
            'allAbilities' => $allAbilities
        ]);
    }

    /**
     * 编辑
     * @param Role $role
     * @return Application|Factory|View
     */
    public function profile(Role $role) {
        $allAbilities = Ability::getTreeData();
        $treeData = [];
        foreach ($allAbilities['view'] as $allAbility) {
            $open = false;
            if (count($allAbility->children)) {
                $open = true;
            }

            $checked = false;
            if(in_array($allAbility->id,$role->abilities()->allRelatedIds()->toArray())) {
                $checked = true;
            }

            array_push($treeData,[
                'id' => $allAbility->id,
                'pId' => $allAbility->parent_id,
                'name' => $allAbility->name,
                'open' => $open,
                'checked' => $checked
            ]);
            foreach ($allAbility->children as $child) {
                $open = false;
                $checked = false;
                if(in_array($child->id,$role->abilities()->allRelatedIds()->toArray())) {
                    $checked = true;
                }
                array_push($treeData,[
                    'id' => $child->id,
                    'pId' => $child->parent_id,
                    'name' => $child->name,
                    'open' => $open,
                    'checked' => $checked
                ]);
            }
        }

        return view('admin.roles.store', [
            'item' => $role,
            'allAbilities' => $allAbilities,
            'treeData' => $treeData
        ]);
    }
}
