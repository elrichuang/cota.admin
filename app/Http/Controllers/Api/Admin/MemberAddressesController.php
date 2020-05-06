<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\MemberAddressResource;
use App\Models\MemberAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberAddressesController extends Controller
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
            $paginationData = MemberAddress::orderBy('id','desc')->paginate($limit);
            $items = MemberAddressResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = MemberAddress::all();
            $items = MemberAddressResource::collection($paginationData);
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
     * @param MemberAddress $memberAddress
     * @return JsonResponse
     */
    public function show(MemberAddress $memberAddress)
    {
        return responseSuccess('详细信息',new MemberAddressResource($memberAddress));
    }
}
