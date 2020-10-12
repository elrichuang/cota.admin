<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\RoleStoreRequest;
use App\Http\Requests\Admin\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    protected $modelClass = 'Role';

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
            $paginationData = Role::orderBy('id','desc')->paginate($limit);
            $items = RoleResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Role::all();
            $items = RoleResource::collection($paginationData);
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
     * @param RoleStoreRequest $request
     * @return JsonResponse
     */
    public function store(RoleStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();

            $entity = Role::create([
                'user_id' => $user->id,
                'name' => $request->name,
            ]);

            $viewIds = [];
            if ($request->view_abilities_ids) {
                $viewIds = json_decode($request->view_abilities_ids);
            }

            $abilitiesIds = $viewIds;
            $entity->setAbilities($abilitiesIds);

            $entity->save();

            return responseSuccess('创建成功', new RoleResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role)
    {
        return responseSuccess('详细信息',new RoleResource($role));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleUpdateRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        try {
            $role->name = $request->name;

            $viewIds = [];
            if ($request->view_abilities_ids) {
                $viewIds = json_decode($request->view_abilities_ids);
            }

            $abilitiesIds = $viewIds;
            $role->setAbilities($abilitiesIds);

            $role->save();

            return responseSuccess('更新成功', new RoleResource($role));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
