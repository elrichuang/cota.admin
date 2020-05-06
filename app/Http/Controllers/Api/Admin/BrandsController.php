<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\BrandStoreRequest;
use App\Http\Requests\Admin\BrandUpdateRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandsController extends Controller
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
            $paginationData = Brand::orderBy('id','desc')->paginate($limit);
            $items = BrandResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Brand::all();
            $items = BrandResource::collection($paginationData);
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
     * @param BrandStoreRequest $request
     * @return JsonResponse
     */
    public function store(BrandStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();

            $entity = Brand::create([
                'user_id' => $user->id,
                'merchant_id'=>$request->merchant_id,
                'store_id'=>$request->store_id,
                'title'=>$request->title,
                'logo'=>$request->logo
            ]);

            $entity->save();

            return responseSuccess('创建成功', new BrandResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Brand $brand
     * @return JsonResponse
     */
    public function show(Brand $brand)
    {
        return responseSuccess('详细信息',new BrandResource($brand));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BrandUpdateRequest $request
     * @param Brand $brand
     * @return JsonResponse
     */
    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        try {
            $brand->merchant_id = $request->merchant_id;
            $brand->store_id = $request->store_id;
            $brand->title = $request->title;
            $brand->logo = $request->logo;
            $brand->save();

            return responseSuccess('更新成功', new BrandResource($brand));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Brand $brand
     * @return JsonResponse
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
