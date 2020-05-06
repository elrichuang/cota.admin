<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\RefundOrderAcceptRequest;
use App\Http\Requests\RefundOrderRejectRequest;
use App\Http\Resources\RefundOrderResource;
use App\libraries\TgPosp;
use App\Models\Order;
use App\Models\OrderSkuStatus;
use App\Models\OrderStatus;
use App\Models\RefundOrder;
use App\Models\RefundOrderStatus;
use EasyWeChat\Factory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RefundOrdersController extends Controller
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
            $paginationData = RefundOrder::orderBy('id','desc')->paginate($limit);
            $items = RefundOrderResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = RefundOrder::all();
            $items = RefundOrderResource::collection($paginationData);
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
     * @param RefundOrder $refundOrder
     * @return JsonResponse
     */
    public function show(RefundOrder $refundOrder)
    {
        return responseSuccess('详细信息',new RefundOrderResource($refundOrder));
    }

    /**
     * 驳回退款
     * @param RefundOrderRejectRequest $request
     * @param RefundOrder $refundOrder
     * @return JsonResponse
     */
    public function reject(RefundOrderRejectRequest $request,RefundOrder $refundOrder)
    {
        $user = auth('admin')->user();

        $memo = $request->memo;

        $refundOrder->status = RefundOrder::STATUS_REFUND_REJECTED;
        $refundOrder->save();

        RefundOrderStatus::addStatus($refundOrder,$memo,null,$user->id);

        // 修改订单状态
        if ($refundOrder->full_refund) {
            $order = $refundOrder->order;

            $order->status = Order::STATUS_REFUND_REJECTED;
            OrderStatus::addStatus($order,$memo,null,$user->id);

            $orderSkus = $order->orderSkus;
            foreach ($orderSkus as $orderSku) {
                $orderSku->status = Order::STATUS_REFUND_REJECTED;
                $orderSku->save();

                OrderSkuStatus::addStatus($orderSku,$memo,null,$user->id);
            }
        }else {
            $orderSku = $refundOrder->orderSku;
            $orderSku->status = Order::STATUS_REFUND_REJECTED;
            $orderSku->save();

            OrderSkuStatus::addStatus($orderSku,$memo,null,$user->id);
        }

        return responseSuccess('驳回退款成功');
    }

    /**
     * 退款通过
     * @param RefundOrderAcceptRequest $request
     * @param RefundOrder $refundOrder
     * @return JsonResponse
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function accept(RefundOrderAcceptRequest $request, RefundOrder $refundOrder) {
        $user = auth('admin')->user();

        $memo = $request->memo;

        // 积分退还
        if ($refundOrder->total_amount_score > 0){
            // 退积分

        }

        $order = $refundOrder->order;

        if ($order->pay_type == Order::PAY_TYPE_WEIXIN) {
            // 微信退款
            if (config('app.use_tgposp_pay')) {
                // 通莞支付
                $tgposp = new TgPosp();
                $result = $tgposp->reverse2($order->trade_no,$refundOrder->no,$refundOrder->total_amount);

                if ($result['status'] == TgPosp::STATUS_SUCCESS) {
                    if ($refundOrder->full_refund) {
                        if ($result['state'] == TgPosp::STATE_REVERSE || $result['state'] == TgPosp::STATE_REFUNDED) {
                            // 退款成功
                        }else {
                            $memo = $result['message'];
                            RefundOrderStatus::addStatus($refundOrder,$memo,null,$user->id);
                            return responseFail('退款失败');
                        }
                    }else {
                        if ($result['state'] == TgPosp::STATE_REVERSE || $result['state'] == TgPosp::STATE_REFUNDED || $result['state'] == TgPosp::STATE_REFUND_PART) {
                            // 退款成功
                        }else {
                            $memo = $result['message'];
                            RefundOrderStatus::addStatus($refundOrder,$memo,null,$user->id);
                            return responseFail('退款失败');
                        }
                    }
                }else {
                    $memo = $result['message'];
                    RefundOrderStatus::addStatus($refundOrder,$memo,null,$user->id);
                    throw new Exception($result['message']);
                }
            }else {
                // 原生微信支付
                // 微信支付
                $config = [
                    'app_id' => config('wechat.pay.isvAppId'),
                    'mch_id' => config('wechat.pay.isvMchId'),
                    'key' => config('wechat.pay.isvKey'),
                    'cert_path'=> config('wechat.pay.isvCertPath'),
                    'key_path'=> config('wechat.pay.isvKeyPath'),
                    'notify_url'> config('wechat.pay.notifyUrl'),
                    /**
                     * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
                     * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
                     */
                    'response_type' => 'array',
                    'log' => [
                        'default' => 'single', // 默认使用的 channel，生产环境可以改为下面的 prod
                        'channels' => config('logging.channels'),
                    ],
                ];

                $app = Factory::payment($config);

                $app->setSubMerchant(config('wechat.pay.mchId'), config('wechat.pay.appId'));

                $totalFee = 1;
                $refundFee = 1;
                if (app()->environment('production'))
                {
                    $totalFee = $order->total_amount * 100;
                    $refundFee = $refundOrder->total_amount * 100;
                }

                $result = $app->refund->byOutTradeNumber($order->no, $refundOrder->no, $totalFee, $refundFee, [
                    // 可在此处传入其他参数，详细参数见微信支付文档
                    'refund_desc' => $memo,
                ]);

                if ($result['return_code'] != 'SUCCESS' || $result['result_code'] != 'SUCCESS') {
                    $memo = $result['err_code_des'];
                    RefundOrderStatus::addStatus($refundOrder,$memo,null,$user->id);
                    throw new Exception($result['err_code_des']);
                }
            }
        }elseif($order->pay_type == Order::PAY_TYPE_ALIPAY) {
            // 支付宝退款
        }else {
            $memo = '退款失败，不支持这种支付方式';
            RefundOrderStatus::addStatus($refundOrder,$memo,null,$user->id);
            return responseFail('退款失败，不支持这种支付方式');
        }

        $refundOrder->status = RefundOrder::STATUS_REFUNDED;
        $refundOrder->save();

        RefundOrderStatus::addStatus($refundOrder,$memo,null,$user->id);

        // 修改订单状态
        if ($refundOrder->full_refund) {
            $order = $refundOrder->order;

            $order->status = Order::STATUS_REFUNDED;
            OrderStatus::addStatus($order,$memo,null,$user->id);

            $orderSkus = $order->orderSkus;
            foreach ($orderSkus as $orderSku) {
                $orderSku->status = Order::STATUS_REFUNDED;
                $orderSku->save();

                OrderSkuStatus::addStatus($orderSku,$memo,null,$user->id);
            }
        }else {
            $orderSku = $refundOrder->orderSku;
            $orderSku->status = Order::STATUS_REFUNDED;
            $orderSku->save();

            OrderSkuStatus::addStatus($orderSku,$memo,null,$user->id);
        }

        return responseSuccess('退款成功');
    }
}
