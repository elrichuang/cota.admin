<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\SpuCategoryStoreRequest;
use App\Http\Requests\Admin\SpuCategoryUpdateRequest;
use App\Http\Resources\SpuCategoryResource;
use App\Models\SpuCategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpuCategoriesController extends Controller
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
            $paginationData = SpuCategory::orderBy('num_sort','desc')
                ->orderBy('id','desc')
                ->paginate($limit);
            $items = SpuCategoryResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = SpuCategory::all();
            $items = SpuCategoryResource::collection($paginationData);
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
     * @param SpuCategoryStoreRequest $request
     * @return JsonResponse
     */
    public function store(SpuCategoryStoreRequest $request)
    {
        try {
            $entity = SpuCategory::create([
                'store_id' => $request->store_id,
                'parent_id'=>$request->parent_id,
                'title'=>$request->title,
                'thumb'=>$request->thumb,
                'num_sort'=>$request->num_sort
            ]);

            $entity->save();

            return responseSuccess('创建成功', new SpuCategoryResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param SpuCategory $spuCategory
     * @return JsonResponse
     */
    public function show(SpuCategory $spuCategory)
    {
        return responseSuccess('详细信息',new SpuCategoryResource($spuCategory));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SpuCategoryUpdateRequest $request
     * @param SpuCategory $spuCategory
     * @return JsonResponse
     */
    public function update(SpuCategoryUpdateRequest $request, SpuCategory $spuCategory)
    {
        try {
            $spuCategory->store_id = $request->store_id;
            $spuCategory->parent_id = $request->parent_id;
            $spuCategory->title = $request->title;
            $spuCategory->thumb = $request->thumb;
            $spuCategory->num_sort = $request->num_sort;
            $spuCategory->save();

            return responseSuccess('更新成功', new SpuCategoryResource($spuCategory));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SpuCategory $spuCategory
     * @return JsonResponse
     */
    public function destroy(SpuCategory $spuCategory)
    {
        try {
            $spuCategory->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
