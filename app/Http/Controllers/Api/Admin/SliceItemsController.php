<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\SliceItemStoreRequest;
use App\Http\Requests\Admin\SliceItemUpdateRequest;
use App\Http\Resources\SliceItemResource;
use App\Models\SliceItem;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SliceItemsController extends Controller
{
    protected $modelClass = 'SliceItem';

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
            $paginationData = SliceItem::orderBy('id','desc')->paginate($limit);
            $items = SliceItemResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = SliceItem::all();
            $items = SliceItemResource::collection($paginationData);
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
     * @param SliceItemStoreRequest $request
     * @return JsonResponse
     */
    public function store(SliceItemStoreRequest $request)
    {
        try {
            $entity = SliceItem::create([
                'slice_id' => $request->slice_id,
                'image'=>$request->image,
                'url'=>$request->url,
                'num_sort'=>$request->num_sort
            ]);

            $entity->save();

            return responseSuccess('创建成功', new SliceItemResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param SliceItem $sliceItem
     * @return JsonResponse
     */
    public function show(SliceItem $sliceItem)
    {
        return responseSuccess('详细信息',new SliceItemResource($sliceItem));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SliceItemUpdateRequest $request
     * @param SliceItem $sliceItem
     * @return JsonResponse
     */
    public function update(SliceItemUpdateRequest $request, SliceItem $sliceItem)
    {
        try {
            $sliceItem->slice_id = $request->slice_id;
            $sliceItem->image = $request->image;
            $sliceItem->url = $request->url;
            $sliceItem->num_sort = $request->num_sort;
            $sliceItem->save();

            return responseSuccess('更新成功', new SliceItemResource($sliceItem));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SliceItem $sliceItem
     * @return JsonResponse
     */
    public function destroy(SliceItem $sliceItem)
    {
        try {
            $sliceItem->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
