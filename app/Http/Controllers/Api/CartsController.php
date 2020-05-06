<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CartStoreRequest;
use App\Http\Requests\CartUpdateRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Sku;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CartsController extends Controller
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
        $limit = $request->get('limit');
        if ($limit)
        {
            $paginationData = Cart::where('status',Cart::STATUS_ADDED)->orderBy('id','desc')->paginate($limit);
            $items = CartResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Cart::where('status',Cart::STATUS_ADDED)->all();
            $items = CartResource::collection($paginationData);
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
     * @param CartStoreRequest $request
     * @return JsonResponse
     */
    public function store(CartStoreRequest $request)
    {
        try {
            $member = auth('api')->user();

            $sku = Sku::where('id',$request->sku_id)->first();
            if (!$sku)
            {
                throw new Exception('商品不存在');
            }

            $entity = Cart::create([
                'member_id' => $member->id,
                'merchant_id' => $sku->spu->store->merchant->id,
                'store_id' => $sku->spu->store->id,
                'spu_id' => $sku->spu->id,
                'sku_id' => $sku->id,
                'payment' => $request->payment,
                'quantity' => $request->quantity,
                'status' => Cart::STATUS_ADDED,
                'ip_address'=>$request->ip(),
                'user_agent'=>$request->userAgent(),
                'year'=>Carbon::now()->year,
                'month'=>Carbon::now()->month,
                'day'=>Carbon::now()->day,
                'hour'=>Carbon::now()->hour,
                'minute'=>Carbon::now()->minute
            ]);

            $entity->save();

            return responseSuccess('添加成功', new CartResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Cart $cart
     * @return JsonResponse
     */
    public function show(Cart $cart)
    {
        return responseSuccess('详细信息',new CartResource($cart));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CartUpdateRequest $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function update(CartUpdateRequest $request, Cart $cart)
    {
        try {

            $cart->quantity = $request->quantity;
            $cart->save();

            return responseSuccess('更新成功', new CartResource($cart));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Cart $cart
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Cart $cart)
    {
        try {
            $cart->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
