<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\OrderSkuResource;
use App\Models\OrderSku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderSkusController extends Controller
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
            $paginationData = OrderSku::orderBy('id','desc')->paginate($limit);
            $items = OrderSkuResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = OrderSku::all();
            $items = OrderSkuResource::collection($paginationData);
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
     * @param OrderSku $orderSkus
     * @return JsonResponse
     */
    public function show(OrderSku $orderSkus)
    {
        return responseSuccess('详细信息',new OrderSkuResource($orderSkus));
    }
}
