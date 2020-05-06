<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\SliceStoreRequest;
use App\Http\Requests\Admin\SliceUpdateRequest;
use App\Http\Resources\SliceResource;
use App\Models\Slice;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SlicesController extends Controller
{
    protected $modelClass = 'Slice';

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
            $paginationData = Slice::orderBy('id','desc')->paginate($limit);
            $items = SliceResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Slice::all();
            $items = SliceResource::collection($paginationData);
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
     * @param SliceStoreRequest $request
     * @return JsonResponse
     */
    public function store(SliceStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();

            $entity = Slice::create([
                'user_id' => $user->id,
                'name'=>$request->name
            ]);

            $entity->save();

            return responseSuccess('创建成功', new SliceResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Slice $slice
     * @return JsonResponse
     */
    public function show(Slice $slice)
    {
        return responseSuccess('详细信息',new SliceResource($slice));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SliceUpdateRequest $request
     * @param Slice $slice
     * @return JsonResponse
     */
    public function update(SliceUpdateRequest $request, Slice $slice)
    {
        try {
            $slice->name = $request->name;
            $slice->save();

            return responseSuccess('更新成功', new SliceResource($slice));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Slice $slice
     * @return JsonResponse
     */
    public function destroy(Slice $slice)
    {
        try {
            $slice->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
