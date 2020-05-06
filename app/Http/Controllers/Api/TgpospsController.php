<?php

namespace App\Http\Controllers\Api;

use App\libraries\TgPosp;
use App\Models\Order;
use App\Models\OrderStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TgpospsController extends Controller
{
    public function notify(Request $request)
    {
        try {
            $returnData = $request->getContent();
            $returnDataArray = json_decode($returnData,true);

            Log::info('通莞支付返回数据',$returnDataArray);

            $lowOrderId = $returnDataArray['lowOrderId'];
            $upOrderId = $returnDataArray['upOrderId'];
            $account = $returnDataArray['account'];
            $channelId = $returnDataArray['channelId'];
            $merchantId = $returnDataArray['merchantId'];
            $payMoney = $returnDataArray['payMoney'];
            $state = $returnDataArray['state']; //订单状态 0:成功，1:失败
            $orderDesc = $returnDataArray['orderDesc'];
            $payTime = $returnDataArray['payTime'];
            $openid = $returnDataArray['openid'];
            $sign = $returnDataArray['sign'];

            //签名校验
            $tgposp = new TgPosp();
            if(!$tgposp->validParams($returnDataArray))
            {
                Log::info('签名校验不通过',$returnDataArray);

                return response($this->responseOk());
            }

            $outTradeNoArray = explode('_',$lowOrderId);
            $orderNo = $outTradeNoArray[0];
            $order = Order::where('no',$orderNo)->firstOrFail();

            if ($state == TgPosp::STATUS_SUCCESS) {
                Order::payOk($order,Order::PAY_TYPE_WEIXIN, $upOrderId);
            }else {
                Order::payFail($order,Order::PAY_TYPE_WEIXIN);
            }

            return response($this->responseOk());
        }catch (Exception $exception) {
            Log::error('支付回调出错'.$exception->getMessage());
            return response($this->responseOk());
        }
    }

    protected function responseOk() {
        return "
            <xml>
              <return_code><![CDATA[SUCCESS]]></return_code>
              <return_msg><![CDATA[OK]]></return_msg>
            </xml>
        ";
    }
}
