<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\AbilityDestroyManyRequest;
use App\Http\Requests\Admin\AbilityStoreRequest;
use App\Http\Requests\Admin\AbilityUpdateRequest;
use App\Http\Resources\AbilityResource;
use App\Models\Ability;
use App\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbilitiesController extends Controller
{
    protected $modelClass = 'Ability';

    public function __construct()
    {
        $this->middleware('refresh.token:admin');

        //$this->authorizeResource(User::class,'user');
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit');
        if ($limit)
        {
            $paginationData = Ability::orderBy('id','desc')->paginate($limit);
            $items = AbilityResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Ability::all();
            $items = AbilityResource::collection($paginationData);
            $total = $paginationData->count();
        }

        return responseSuccess('信息列表',[
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AbilityStoreRequest $request
     * @return JsonResponse
     */
    public function store(AbilityStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();

            $parent_id = $request->parent_id;
            $icon =$request->icon;
            $show_on_menu = boolval($request->show_on_menu);
            $use_url = boolval($request->use_url);

            if ($request->type == 'api') {
                $parent_id = 0;
                $icon = null;
                $show_on_menu = false;
                $use_url = false;
            }

            $entity = Ability::create([
                'user_id'=>$user->id,
                'name' => $request->name,
                'parent_id' => $parent_id,
                'alias' => strval($request->alias),
                'icon' => $icon,
                'num_sort'=>$request->num_sort,
                'remark' => $request->remark,
                'url' => $request->url,
                'type' => $request->type,
                'status' => $request->status,
                'show_on_menu' => $show_on_menu,
                'use_url' => $use_url
            ]);

            return responseSuccess('创建成功', new AbilityResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Ability $ability
     * @return JsonResponse
     */
    public function show(Ability $ability)
    {
        return responseSuccess('详细信息',new AbilityResource($ability));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AbilityUpdateRequest $request
     * @param Ability $ability
     * @return JsonResponse
     */
    public function update(AbilityUpdateRequest $request, Ability $ability)
    {
        try {
            $parent_id = $request->parent_id;
            $icon =$request->icon;
            $show_on_menu = boolval($request->show_on_menu);
            $use_url = boolval($request->use_url);

            if ($request->type == 'api') {
                $parent_id = 0;
                $icon = null;
                $show_on_menu = false;
                $use_url = false;
            }

            $ability->parent_id = $parent_id;
            $ability->name = $request->name;
            $ability->alias = strval($request->alias);
            $ability->remark = $request->remark;
            $ability->url = $request->url;
            $ability->status = $request->status;
            $ability->type = $request->type;
            $ability->num_sort = $request->num_sort;
            $ability->icon = $icon;
            $ability->show_on_menu = $show_on_menu;
            $ability->use_url = $use_url;
            $ability->save();

            return responseSuccess('更新成功', new AbilityResource($ability));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ability $ability
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Ability $ability)
    {
        try {
            $ability->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    public function getTreeData()
    {
        try {
            $outputData = Ability::getTreeData();

            return responseSuccess('树形控件数据',$outputData);
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
