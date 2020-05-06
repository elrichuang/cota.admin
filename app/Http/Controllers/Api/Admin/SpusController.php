<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\SpuStoreRequest;
use App\Http\Requests\Admin\SpuUpdateRequest;
use App\Http\Resources\SpuResource;
use App\Models\Spu;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpusController extends Controller
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
            $paginationData = Spu::orderBy('on_top_at','desc')
                ->orderBy('num_sort','desc')
                ->orderBy('id','desc')
                ->paginate($limit);
            $items = SpuResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Spu::all();
            $items = SpuResource::collection($paginationData);
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
     * @param  SpuStoreRequest  $request
     * @return JsonResponse
     */
    public function store(SpuStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();
            $on_top_at = null;
            if ($request->on_top)
            {
                $on_top_at = now();
            }
            $recommend_at = null;
            if ($request->recommend)
            {
                $recommend_at = now();
            }
            $entity = Spu::create([
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'store_id' => $request->store_id,
                'name' => $request->name,
                'spu_no' => $request->spu_no,
                'thumb' => $request->thumb,
                'num_sort' => $request->num_sort,
                'on_top_at' => $on_top_at,
                'recommend_at' => $recommend_at,
                'published_at' =>$request->published_at,
            ]);

            $entity->save();

            return responseSuccess('创建成功', new SpuResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Spu $spus
     * @return JsonResponse
     */
    public function show(Spu $spus)
    {
        return responseSuccess('详细信息',new SpuResource($spus));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SpuUpdateRequest $request
     * @param Spu $spus
     * @return JsonResponse
     */
    public function update(SpuUpdateRequest $request, Spu $spus)
    {
        try {
            $on_top_at = null;
            if (boolval($request->on_top))
            {
                $on_top_at = Carbon::now()->toDateTimeString();
            }

            $recommend_at = null;
            if ($request->recommend)
            {
                $recommend_at = Carbon::now()->toDateTimeString();
            }
            $spus->category_id = $request->category_id;
            $spus->store_id = $request->store_id;
            $spus->brand_id = $request->brand_id;
            $spus->name = $request->name;
            $spus->spu_no = $request->spu_no;
            $spus->thumb = $request->get('thumb');
            $spus->num_sort = $request->num_sort;
            $spus->on_top_at = $on_top_at;
            $spus->recommend_at = $recommend_at;
            $spus->published_at = $request->published_at;
            $spus->save();

            return responseSuccess('更新成功', new SpuResource($spus));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Spu $spu
     * @return JsonResponse
     */
    public function destroy(Spu $spu)
    {
        try {
            $spu->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
