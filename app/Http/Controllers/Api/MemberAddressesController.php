<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\MemberAddressStoreRequest;
use App\Http\Requests\MemberAddressUpdateRequest;
use App\Http\Resources\MemberAddressResource;
use App\Models\MemberAddress;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberAddressesController extends Controller
{
    public function __construct()
    {
        $this->middleware('refresh.token:api');

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
        $member = auth('api')->user();

        $limit = $request->get('limit');
        if ($limit)
        {
            $paginationData = MemberAddress::where('member_id',$member->id)->orderBy('id','desc')->paginate($limit);
            $items = MemberAddressResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = MemberAddress::where('member_id',$member->id)->all();
            $items = MemberAddressResource::collection($paginationData);
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
     * @param MemberAddressStoreRequest $request
     * @return JsonResponse
     */
    public function store(MemberAddressStoreRequest $request)
    {
        try {
            $member = auth('api')->user();

            $entity = MemberAddress::create([
                'member_id' => $member->id,
                'consignee' => $request->consignee,
                'phone' => $request->phone,
                'province' => $request->province,
                'city' => $request->city,
                'address' => $request->address
            ]);

            $entity->save();

            return responseSuccess('创建成功', new MemberAddressResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
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

    /**
     * Update the specified resource in storage.
     *
     * @param MemberAddressUpdateRequest $request
     * @param MemberAddress $memberAddress
     * @return JsonResponse
     */
    public function update(MemberAddressUpdateRequest $request, MemberAddress $memberAddress)
    {
        try {

            $memberAddress->consignee = $request->consignee;
            $memberAddress->phone = $request->phone;
            $memberAddress->province = $request->province;
            $memberAddress->city = $request->city;
            $memberAddress->address = $request->address;
            $memberAddress->save();

            return responseSuccess('更新成功', new MemberAddressResource($memberAddress));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MemberAddress $memberAddress
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(MemberAddress $memberAddress)
    {
        try {
            $memberAddress->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
