<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\OrderSkuStatusResource;
use App\Models\OrderSkuStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderSkuStatusesController extends Controller
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
            $paginationData = OrderSkuStatus::orderBy('id','desc')->paginate($limit);
            $items = OrderSkuStatusResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = OrderSkuStatus::all();
            $items = OrderSkuStatusResource::collection($paginationData);
            $total = $paginationData->count();
        }

        return responseSuccess('信息列表',[
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param OrderSkuStatus $orderSkuStatus
     * @return JsonResponse
     */
    public function show(OrderSkuStatus $orderSkuStatus)
    {
        return responseSuccess('详细信息',new OrderSkuStatusResource($orderSkuStatus));
    }

}
