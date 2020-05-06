<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\StoreStoreRequest;
use App\Http\Requests\Admin\StoreUpdateRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoresController extends Controller
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
            $paginationData = Store::orderBy('id','desc')->paginate($limit);
            $items = StoreResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Store::all();
            $items = StoreResource::collection($paginationData);
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
     * @param StoreStoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();

            $entity = Store::create([
                'user_id' => $user->id,
                'merchant_id'=>$request->merchant_id,
                'nickname'=>$request->nickname,
                'name'=>$request->name,
                'weixin_qrcode_image' => $request->weixin_qrcode_image,
                'weixin_share_content' => $request->weixin_share_content,
                'thumb' => $request->thumb,
            ]);

            $entity->save();

            return responseSuccess('创建成功', new StoreResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Store $store
     * @return JsonResponse
     */
    public function show(Store $store)
    {
        return responseSuccess('详细信息',new StoreResource($store));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreUpdateRequest $request
     * @param Store $store
     * @return JsonResponse
     */
    public function update(StoreUpdateRequest $request, Store $store)
    {
        try {
            $store->merchant_id = $request->merchant_id;
            $store->nickname = $request->nickname;
            $store->name = $request->name;
            $store->thumb = $request->thumb;
            $store->weixin_qrcode_image = $request->weixin_qrcode_image;
            $store->weixin_share_content = $request->weixin_share_content;
            $store->save();

            return responseSuccess('更新成功', new StoreResource($store));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Store $store
     * @return JsonResponse
     */
    public function destroy(Store $store)
    {
        try {
            $store->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
