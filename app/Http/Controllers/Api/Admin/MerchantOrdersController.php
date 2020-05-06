<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\MerchantOrderResource;
use App\Models\MerchantOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantOrdersController extends Controller
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
            $paginationData = MerchantOrder::orderBy('id','desc')->paginate($limit);
            $items = MerchantOrderResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = MerchantOrder::all();
            $items = MerchantOrderResource::collection($paginationData);
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
     * @param MerchantOrder $merchantOrder
     * @return JsonResponse
     */
    public function show(MerchantOrder $merchantOrder)
    {
        return responseSuccess('详细信息',new MerchantOrderResource($merchantOrder));
    }
}
