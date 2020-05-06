<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\ExpressUpdateRequest;
use App\Http\Requests\Admin\ExpressStoreRequest;
use App\Http\Resources\ExpressResource;
use App\Models\Express;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpressesController extends Controller
{
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
            $paginationData = Express::orderBy('id','desc')->paginate($limit);
            $items = ExpressResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Express::all();
            $items = ExpressResource::collection($paginationData);
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
     * @param ExpressStoreRequest $request
     * @return JsonResponse
     */
    public function store(ExpressStoreRequest $request)
    {
        try {
            $entity = Express::create([
                'name'=>$request->name,
                'alias'=>$request->alias,
            ]);

            $entity->save();

            return responseSuccess('创建成功', new ExpressResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Express  $express
     * @return JsonResponse
     */
    public function show(Express $express)
    {
        return responseSuccess('信息详情',new ExpressResource($express));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExpressUpdateRequest $request
     * @param Express $express
     * @return JsonResponse
     */
    public function update(ExpressUpdateRequest $request, Express $express)
    {
        try {
            $express->name = $request->name;
            $express->alias = $request->alias;
            $express->save();

            return responseSuccess('更新成功', new ExpressResource($express));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Express $express
     * @return JsonResponse
     */
    public function destroy(Express $express)
    {
        try {
            $express->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
