<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\SkuStoreRequest;
use App\Http\Requests\Admin\SkuUpdateRequest;
use App\Http\Resources\SkuResource;
use App\Models\Sku;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkusController extends Controller
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
            $paginationData = Sku::orderBy('id','desc')->paginate($limit);
            $items = SkuResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Sku::all();
            $items = SkuResource::collection($paginationData);
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
     * @param  SkuStoreRequest  $request
     * @return JsonResponse
     */
    public function store(SkuStoreRequest $request)
    {
        try {
            $entity = Sku::create([
                'spu_id' => $request->spu_id,
                'name' => $request->name,
                'sku_no' => $request->sku_no,
                'images' => implode(',',$request->images),
                'price' => $request->price,
                'score' => $request->score,
                'less_price' => $request->less_price,
                'less_score' => $request->less_score,
                'cost' =>$request->cost,
                'commission' => $request->commission,
                'norms' => $request->norms,
                'color' => $request->color,
                'material' => $request->material,
                'tags' => implode(',',$request->tags),
                'content' => $request->get('content'),
                'num_sort' => $request->num_sort,
                'num_stock' => $request->num_stock,
                'status'=>$request->status
            ]);

            $entity->save();

            return responseSuccess('创建成功', new SkuResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Sku $skus
     * @return JsonResponse
     */
    public function show(Sku $skus)
    {
        return responseSuccess('详细信息',new SkuResource($skus));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SkuUpdateRequest $request
     * @param Sku $skus
     * @return JsonResponse
     */
    public function update(SkuUpdateRequest $request, Sku $skus)
    {
        try {
            $skus->spu_id = $request->spu_id;
            $skus->name = $request->name;
            $skus->sku_no = $request->sku_no;
            $skus->images = implode(',',$request->images);
            $skus->price = $request->price;
            $skus->score = $request->score;
            $skus->less_price = $request->less_price;
            $skus->less_score = $request->less_score;
            $skus->cost = $request->cost;
            $skus->commission = $request->commission;
            $skus->norms = $request->norms;
            $skus->color = $request->color;
            $skus->material = $request->material;
            $skus->tags = implode(',',$request->tags);
            $skus->status = $request->status;
            $skus->num_stock = $request->num_stock;
            $skus->content = $request->get('content');
            $skus->num_sort = $request->num_sort;
            $skus->save();

            return responseSuccess('更新成功', new SkuResource($skus));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Sku $skus
     * @return JsonResponse
     */
    public function destroy(Sku $skus)
    {
        try {
            $skus->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
