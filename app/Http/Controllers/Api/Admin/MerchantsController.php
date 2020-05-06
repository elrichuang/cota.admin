<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\MerchantStoreRequest;
use App\Http\Requests\Admin\MerchantUpdateRequest;
use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantsController extends Controller
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
            $paginationData = Merchant::orderBy('id','desc')->paginate($limit);
            $items = MerchantResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Merchant::all();
            $items = MerchantResource::collection($paginationData);
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
     * @param MerchantStoreRequest $request
     * @return JsonResponse
     */
    public function store(MerchantStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();

            $entity = Merchant::create([
                'user_id' => $user->id,
                'nickname'=>$request->nickname,
                'phone'=>$request->phone,
                'password'=>bcrypt($request->password),
                'organization_name'=>$request->organization_name,
                'logo'=>$request->logo,
                'contact_man'=>$request->contact_man,
                'contact_email'=>$request->contact_email,
                'contact_tel'=>$request->contact_tel,
                'contact_address'=>$request->contact_address,
                'weixin_pay_sub_mch_id'=>$request->weixin_pay_sub_mch_id,
                'alipay_appid'=>$request->alipay_appid,
                'alipay_app_secret'=>$request->alipay_app_secret,
                'tg_account'=>$request->tg_account,
                'tg_key'=>$request->tg_key,
                'for_test'=>$request->for_test,
                'memo'=>$request->memo
            ]);

            $entity->save();

            return responseSuccess('创建成功', new MerchantResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Merchant $merchant
     * @return JsonResponse
     */
    public function show(Merchant $merchant)
    {
        return responseSuccess('详细信息',new MerchantResource($merchant));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MerchantUpdateRequest $request
     * @param Merchant $merchant
     * @return JsonResponse
     */
    public function update(MerchantUpdateRequest $request, Merchant $merchant)
    {
        try {
            $merchant->nickname = $request->nickname;
            $merchant->phone = $request->phone;
            $merchant->organization_name = $request->organization_name;
            $merchant->logo = $request->logo;
            $merchant->contact_man = $request->contact_man;
            $merchant->contact_email = $request->contact_email;
            $merchant->contact_tel = $request->contact_tel;
            $merchant->contact_address = $request->contact_address;
            $merchant->weixin_pay_sub_mch_id = $request->weixin_pay_sub_mch_id;
            $merchant->alipay_appid = $request->alipay_appid;
            $merchant->alipay_app_secret = $request->alipay_app_secret;
            $merchant->tg_account = $request->tg_account;
            $merchant->tg_key = $request->tg_key;
            $merchant->for_test = $request->for_test;
            $merchant->memo = $request->memo;
            $merchant->save();

            return responseSuccess('更新成功', new MerchantResource($merchant));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Merchant $merchant
     * @return JsonResponse
     */
    public function destroy(Merchant $merchant)
    {
        try {
            $merchant->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
