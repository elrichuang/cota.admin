<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrderCancelRequest;
use App\Http\Requests\OrderCheckCartRequest;
use App\Http\Requests\OrderConfirmRequest;
use App\Http\Requests\OrderPayRequest;
use App\Http\Requests\OrderReceiveRequest;
use App\Http\Resources\OrderResource;
use App\libraries\TgPosp;
use App\Models\Cart;
use App\Models\Express;
use App\Models\MemberAddress;
use App\Models\MerchantOrder;
use App\Models\Order;
use App\Models\OrderSku;
use App\Models\OrderSkuStatus;
use App\Models\OrderStatus;
use App\Models\RefundOrder;
use App\Models\RefundOrderStatus;
use App\Models\Sku;
use EasyWeChat\Factory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class OrdersController extends Controller
{
    protected $orderSkus = [];

    public function __construct()
    {
        $this->middleware('refresh.token:api');

        //$this->authorizeResource(User::class,'user');
    }

    /**
     * 购物车检查
     * @param OrderCheckCartRequest $request
     * @return JsonResponse
     */
    public function checkCart(OrderCheckCartRequest $request)
    {
        $cartIds = explode(',',$request->cart_ids);
        $directBuy = boolval($request->direct_buy);
        $groupBuy = boolval($request->group_buy);
        $skuId = $request->sku_id;
        $quantity = $request->quantity;
        $payment = $request->payment;
        $forOrder = $request->for_order;

        $totalFee = 0;
        $totalFeeScore = 0;
        $totalDiscount = 0;
        $totalDiscountScore = 0;
        $totalAmount = 0;
        $totalAmountScore = 0;

        if ($directBuy) {
            // 直接购买
            $sku = Sku::findOrFail($skuId);
            if ($forOrder) {
                $spu = $sku->spu;
                $store = $spu->store;
                $merchant = $store->merchant;

                $orderSku = new OrderSku();
                $orderSku->merchant_id = $merchant->id;
                $orderSku->store_id = $store->id;
                $orderSku->spu_id = $spu->id;
                $orderSku->sku_id = $sku->id;
                $orderSku->payment = $payment;
                $orderSku->cost = $sku->cost;
                // 判断团购
                $orderSku->commission = $sku->commission;
                $orderSku->quantity = $quantity;
                $orderSku->memo = '';
                $orderSku->status = Order::STATUS_UNPAID;
            }

            if ($groupBuy) {
                // 团购
                if ($payment == Cart::PAYMENT_CASH) {
                    $totalFee += $quantity * $sku->price_group_buy;
                    $totalAmount += $quantity * $sku->price_group_buy;
                    if ($forOrder) {
                        $orderSku->original_sku_price = $sku->price_group_buy;
                        $orderSku->sku_price = $sku->price_group_buy;
                        $orderSku->total_fee = $sku->price_group_buy * $quantity;
                        $orderSku->total_amount = $sku->price_group_buy * $quantity;
                        $orderSku->total_discount = $orderSku->total_fee - $orderSku->total_amount;
                    }
                }elseif ($payment == Cart::PAYMENT_SCORE) {
                    $totalFeeScore += $quantity * $sku->score_group_buy;
                    $totalAmountScore += $quantity * $sku->score_group_buy;

                    if ($forOrder) {
                        $orderSku->original_sku_score = $sku->score_group_buy;
                        $orderSku->sku_score = $sku->score_group_buy;
                        $orderSku->total_fee_score = $sku->score_group_buy * $quantity;
                        $orderSku->total_amount_score = $sku->score_group_buy * $quantity;
                        $orderSku->total_discount_score = $orderSku->total_fee_score - $orderSku->total_amount_score;
                    }
                }elseif ($payment == Cart::PAYMENT_BOTH) {
                    $totalFee += $quantity * $sku->less_price_group_buy;
                    $totalAmount += $quantity * $sku->less_price_group_buy;

                    $totalFeeScore += $quantity * $sku->less_score_group_buy;
                    $totalAmountScore += $quantity * $sku->less_score_group_buy;

                    if ($forOrder) {
                        $orderSku->original_sku_price = $sku->less_price_group_buy;
                        $orderSku->sku_price = $sku->less_price_group_buy;
                        $orderSku->total_fee = $sku->less_price_group_buy * $quantity;
                        $orderSku->total_amount = $sku->less_price_group_buy * $quantity;
                        $orderSku->total_discount = $orderSku->total_fee - $orderSku->total_amount;

                        $orderSku->original_sku_score = $sku->less_score_group_buy;
                        $orderSku->sku_score = $sku->less_score_group_buy;
                        $orderSku->total_fee_score = $sku->less_score_group_buy * $quantity;
                        $orderSku->total_amount_score = $sku->less_score_group_buy * $quantity;
                        $orderSku->total_discount_score = $orderSku->total_fee_score - $orderSku->total_amount_score;
                    }
                }
            }else {
                // 一般直接购买
                if ($payment == Cart::PAYMENT_CASH) {
                    $totalFee += $quantity * $sku->price;
                    $totalAmount += $quantity * $sku->price;
                    if ($forOrder) {
                        $orderSku->original_sku_price = $sku->price;
                        $orderSku->sku_price = $sku->price;
                        $orderSku->total_fee = $sku->price * $quantity;
                        $orderSku->total_amount = $sku->price * $quantity;
                        $orderSku->total_discount = $orderSku->total_fee - $orderSku->total_amount;
                    }
                }elseif ($payment == Cart::PAYMENT_SCORE) {
                    $totalFeeScore += $quantity * $sku->score;
                    $totalAmountScore += $quantity * $sku->score;
                    if ($forOrder) {
                        $orderSku->original_sku_score = $sku->score;
                        $orderSku->sku_score = $sku->score;
                        $orderSku->total_fee_score = $sku->score * $quantity;
                        $orderSku->total_amount_score = $sku->score * $quantity;
                        $orderSku->total_discount_score = $orderSku->total_fee_score - $orderSku->total_amount_score;
                    }
                }elseif ($payment == Cart::PAYMENT_BOTH) {
                    $totalFee += $quantity * $sku->less_price;
                    $totalAmount += $quantity * $sku->less_price;

                    $totalFeeScore += $quantity * $sku->less_score;
                    $totalAmountScore += $quantity * $sku->less_score;

                    if ($forOrder) {
                        $orderSku->original_sku_price = $sku->less_price;
                        $orderSku->sku_price = $sku->less_price;
                        $orderSku->total_fee = $sku->less_price * $quantity;
                        $orderSku->total_amount = $sku->less_price * $quantity;
                        $orderSku->total_discount = $orderSku->total_fee - $orderSku->total_amount;

                        $orderSku->original_sku_score = $sku->less_score;
                        $orderSku->sku_score = $sku->less_score;
                        $orderSku->total_fee_score = $sku->less_score * $quantity;
                        $orderSku->total_amount_score = $sku->less_score * $quantity;
                        $orderSku->total_discount_score = $orderSku->total_fee_score - $orderSku->total_amount_score;
                    }
                }
            }

            if ($forOrder) {
                $this->orderSkus[] = $orderSku;
            }

            $totalDiscount = $totalFee - $totalAmount;
            $totalDiscountScore = $totalFeeScore - $totalAmountScore;
        }else {
            // 一般下单
            $carts = Cart::whereIn('id',$cartIds)->get();

            // 计算订单价钱
            foreach ($carts as $cart) {
                $sku = $cart->sku;
                if ($forOrder) {
                    $spu = $sku->spu;
                    $store = $spu->store;
                    $merchant = $store->merchant;

                    $orderSku = new OrderSku();
                    $orderSku->merchant_id = $merchant->id;
                    $orderSku->store_id = $store->id;
                    $orderSku->spu_id = $spu->id;
                    $orderSku->sku_id = $sku->id;
                    $orderSku->payment = $cart->payment;
                    $orderSku->cost = $sku->cost;
                    // 判断团购
                    $orderSku->commission = $sku->commission;
                    $orderSku->quantity = $cart->quantity;
                    $orderSku->memo = '';
                    $orderSku->status = Order::STATUS_UNPAID;
                }

                if ($cart->payment == Cart::PAYMENT_CASH) {
                    $totalFee += $cart->quantity * $sku->price;
                    $totalAmount += $cart->quantity * $sku->price;

                    if ($forOrder) {
                        $orderSku->original_sku_price = $sku->price;
                        $orderSku->sku_price = $sku->price;
                        $orderSku->total_fee = $sku->price * $cart->quantity;
                        $orderSku->total_amount = $sku->price * $cart->quantity;
                        $orderSku->total_discount = $orderSku->total_fee - $orderSku->total_amount;
                    }
                }elseif ($cart->payment == Cart::PAYMENT_SCORE) {
                    $totalFeeScore += $cart->quantity * $sku->score;
                    $totalAmountScore += $cart->quantity * $sku->score;

                    if ($forOrder) {
                        $orderSku->original_sku_score = $sku->score;
                        $orderSku->sku_score = $sku->score;
                        $orderSku->total_fee_score = $sku->score * $cart->quantity;
                        $orderSku->total_amount_score = $sku->score * $cart->quantity;
                        $orderSku->total_discount_score = $orderSku->total_fee_score - $orderSku->total_amount_score;
                    }
                }elseif ($cart->payment == Cart::PAYMENT_BOTH) {
                    $totalFee += $cart->quantity * $sku->less_price;
                    $totalAmount += $cart->quantity * $sku->less_price;

                    $totalFeeScore += $cart->quantity * $sku->less_score;
                    $totalAmountScore += $cart->quantity * $sku->less_score;

                    if ($forOrder) {
                        $orderSku->original_sku_price = $sku->less_price;
                        $orderSku->sku_price = $sku->less_price;
                        $orderSku->total_fee = $sku->less_price * $cart->quantity;
                        $orderSku->total_amount = $sku->less_price * $cart->quantity;
                        $orderSku->total_discount = $orderSku->total_fee - $orderSku->total_amount;

                        $orderSku->original_sku_score = $sku->less_score;
                        $orderSku->sku_score = $sku->less_score;
                        $orderSku->total_fee_score = $sku->less_score * $cart->quantity;
                        $orderSku->total_amount_score = $sku->less_score * $cart->quantity;
                        $orderSku->total_discount_score = $orderSku->total_fee_score - $orderSku->total_amount_score;
                    }
                }

                if ($forOrder) {
                    $this->orderSkus[] = $orderSku;
                }
            }

            $totalDiscount = $totalFee - $totalAmount;
            $totalDiscountScore = $totalFeeScore - $totalAmountScore;
        }

        return responseSuccess('优惠计算', [
            'total_fee' => $totalFee,
            'total_fee_score' => $totalFeeScore,
            'total_amount' => $totalAmount,
            'total_amount_score' => $totalAmountScore,
            'total_discount' => $totalDiscount,
            'total_discount_score' => $totalDiscountScore
        ]);
    }

    /**
     * 确认订单
     * @param OrderConfirmRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function confirm(OrderConfirmRequest $request)
    {
        $member = auth('api')->user();

        $memberAddressId = $request->member_address_id;
        $deliveryType = $request->delivery_type;
        $memo = $request->memo;
        $cartIds = explode(',',$request->cart_ids);

        $carts = Cart::whereIn('id',$cartIds)->get();
        foreach ($carts as $cart) {
            if ($cart->member_id != $member->id) {
                throw new UnauthorizedException('没有操作权限');
            }
        }

        // 用于下单计费
        $request->for_order = true;

        // 计算订单价钱
        $returnJson = $this->checkCart($request);
        $returnData = $returnJson->getData()->data;

        $totalFee = $returnData->total_fee;
        $totalFeeScore = $returnData->total_fee_score;
        $totalDiscount = $returnData->total_discount;
        $totalDiscountScore = $returnData->total_discount_score;
        $totalAmount = $returnData->total_amount;
        $totalAmountScore = $returnData->total_amount_score;

        $order = new Order();
        $order->member_id = $member->id;
        $order->no = Order::generateNo();
        $order->type = Order::TYPE_NORMAL;
        $order->memo = $memo;
        $order->delivery_type = $deliveryType;
        $order->pay_type = Order::PAY_TYPE_NONE;
        $order->status = Order::STATUS_UNPAID;
        $order->cash_paid_status = Order::STATUS_UNPAID;
        $order->score_paid_status = Order::STATUS_UNPAID;
        $order->total_fee = $totalFee;
        $order->total_fee_score = $totalFeeScore;
        $order->total_discount = $totalDiscount;
        $order->total_discount_score = $totalDiscountScore;
        $order->total_amount = $totalAmount;
        $order->total_amount_score = $totalAmountScore;
        $order->ip_address = $request->ip();
        $order->user_agent = $request->userAgent();
        $order->year = Carbon::now()->year;
        $order->month = Carbon::now()->month;
        $order->day = Carbon::now()->day;
        $order->hour = Carbon::now()->hour;
        $order->minute = Carbon::now()->minute;
        // 判断配送方式
        if ($deliveryType == Order::DELIVERY_TYPE_EXPRESS) {
            // 快递，获取地址
            $memberAddress = MemberAddress::findOrFail($memberAddressId);

            if ($memberAddress->member_id != $member->id) {
                throw new Exception('地址不正确，请重新选择');
            }

            $order->consignee = $memberAddress->consignee;
            $order->phone = $memberAddress->phone;
            $order->province = $memberAddress->province;
            $order->city = $memberAddress->city;
            $order->address = $memberAddress->address;
        }

        // TODO 积分检查，暂无此功能
        // ...

        // 生成会员订单，付款成功才生成商家订单，退款，查快递按 order_sku 退款
        $order->save();

        // 记录订单状态
        OrderStatus::addStatus($order,'确认订单',$member->id);

        // 生成订单 SKU
        foreach ($this->orderSkus as $orderSku) {
            $orderSku->order_id = $order->id;
            $orderSku->save();

            // 记录订单 SKU 状态
            OrderSkuStatus::addStatus($orderSku,'确认订单',$member->id);

            // 减少 SKU 库存
            $orderSku->sku->decrement('num_stock', $orderSku->quantity);
        }

        // 更新购物车记录状态
        foreach ($carts as $cart) {
            $cart->status = Cart::STATUS_ORDERED;
            $cart->save();
        }

        // 返回成功信息
        return responseSuccess('下单成功',[
            'no' => $order->no
        ]);

    }

    /**
     * 订单列表
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request)
    {
        $member = auth('api')->user();

        $limit = $request->get('limit');
        $status = $request->get('status');
        if ($limit)
        {
            $paginationData = Order::where('member_id', $member->id)->orderBy('id','desc')->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })->paginate($limit);
            $items = OrderResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Order::where('member_id', $member->id)->orderBy('id','desc')->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })->all();
            $items = OrderResource::collection($paginationData);
            $total = $paginationData->count();
        }

        return responseSuccess('订单列表',[
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * 订单详情
     * @param Order $order
     * @return JsonResponse
     */
    public function detail(Order $order)
    {
        return responseSuccess('订单详细信息',new OrderResource($order));
    }

    /**
     * 订单支付
     * @param OrderPayRequest $request
     * @return JsonResponse
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function pay(OrderPayRequest $request)
    {
        $no = $request->no;
        $payType = $request->pay_type;
        $tradeType = $request->trade_type;
        $clientType = $request->client_type;
        $order = Order::where('no', $no)->firstOrFail();

        $member = auth('api')->user();

        if ($order->member_id != $member->id) {
            throw new UnauthorizedException('没有操作权限');
        }

        // 判断订单状态
        if ($order->status > Order::STATUS_UNPAID) {
            return responseFail('订单不能支付');
        }

        // 是否需要积分，检查积分是否够
        if ($order->total_amount_score > 0 && $order->score_paid_status == Order::STATUS_UNPAID) {
            // 检查会员积分是否足够
            // ...
        }

        if ($order->total_amount > 0 && $order->cash_paid_status == Order::STATUS_UNPAID) {
            // 现金支付
            if ($payType == Order::PAY_TYPE_WEIXIN) {
                $body = config('app.name').'-商城购物';
                if (config('app.use_tgposp_pay')) {
                    // 通莞支付
                    $notifyUrl = url('tgposps.notify');

                    $returnUrl = url('orders.detail', $order);

                    $attach = '';
                    $tgposp = new TgPosp();
                    $result = null;
                    $outTradeNo = $order->no . '_' . random_string('numeric', 4);
                    if ($clientType == Order::CLIENT_TYPE_H5) {
                        $result = $tgposp->allQrcodePay([
                            'lowOrderId'=>$outTradeNo,
                            'payMoney'=>$order->total_amount,
                            'notifyUrl'=>$notifyUrl,
                            'returnUrl'=>$returnUrl,
                            'attach'=>$attach,
                            'body'=>$body
                        ]);
                    }

                    if ($clientType == Order::CLIENT_TYPE_MINIAPP) {
                        $result = $tgposp->wxJspay([
                            'lowOrderId'=>$outTradeNo,
                            'payMoney'=>$order->total_amount,
                            'notifyUrl'=>$notifyUrl,
                            'returnUrl'=>$returnUrl,
                            'attach'=>$attach,
                            'body'=>$body,
                            'isMinipg'=>'1',
                            'openId'=>$member->openid,
                            'appId'=>config('wechat.pay.appId'),
                        ]);
                    }

                    if ($result) {
                        if ($result['status'] == TgPosp::STATUS_SUCCESS) {
                            return responseSuccess('预支付成功',[
                                'url' => $result['pay_url']
                            ]);
                        }else {
                            throw new Exception($result['message']);
                        }
                    }else {
                        throw new Exception('不支持这种支付方式');
                    }
                }

                // 往下为微信支付
                if (!$member->openid) {
                    responseFail('账号未绑定微信，无法用微信支付');
                }

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

                if (app()->environment('production'))
                {
                    $totalAmount = $order->total_amount * 100;
                }else
                {
                    $totalAmount = 1;
                }

                $result = $app->order->unify([
                    'version'=>'1.0',
                    'body' => $body,
                    'out_trade_no' => $order->no,
                    'total_fee' => $totalAmount,
                    'spbill_create_ip' => config('wechat.pay.spbillCreateIp'), // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
                    'notify_url' => config('wechat.pay.notifyUrl'), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                    'trade_type' => $tradeType, // 请对应换成你的支付方式对应的值类型
                    'sub_openid'=>$member->weixin_openid
                ]);

                Log::info('微信支付统一下单',$result);

                // $result:
                //{
                //    "return_code": "SUCCESS",
                //    "return_msg": "OK",
                //    "appid": "wx2421b1c4390ec4sb",
                //    "mch_id": "10000100",
                //    "nonce_str": "IITRi8Iabbblz1J",
                //    "openid": "oUpF8uMuAJO_M2pxb1Q9zNjWeSs6o",
                //    "sign": "7921E432F65EB8ED0CE9755F0E86D72F2",
                //    "result_code": "SUCCESS",
                //    "prepay_id": "wx201411102639507cbf6ffd8b0779950874",
                //    "trade_type": "JSAPI"
                //}

                if ($result['return_code'] == 'SUCCESS') {
                    $prepayId = $result['prepay_id'];

                    $jsSdk = $app->jssdk;

                    $config = null;

                    if ($tradeType == Order::TRADE_TYPE_JSAPI) {
                        // JSSDK
                        if ($clientType == Order::CLIENT_TYPE_H5) {
                            $config = $jsSdk->sdkConfig($prepayId); // 返回数组
                        }

                        if ($clientType == Order::CLIENT_TYPE_MINIAPP) {
                            // 小程序
                            $config = $jsSdk->bridgeConfig($prepayId, false);
                        }

                    }elseif ($tradeType == Order::TRADE_TYPE_JSAPI) {
                        // APP
                        $config = $jsSdk->appConfig($prepayId);
                    }else{
                        return responseFail('暂不支持这类终端的微信支付方式');
                    }

                    if (!$config) {
                        return responseFail('微信支付失败');
                    }

                    return responseSuccess('发起微信支付成功',[
                        'config' => $config
                    ]);
                }else {
                    return responseFail('微信支付失败'.$result['return_msg']);
                }
            }elseif ($payType == Order::PAY_TYPE_ALIPAY) {
                // 支付宝
                return responseFail('暂未接入支付宝');
            }else {
                return responseFail('不支持该支付方式');
            }
        }

        return responseSuccess('无需支付');
    }

    /**
     * 已收货
     * @param OrderReceiveRequest $request
     * @return JsonResponse
     */
    public function receive(OrderReceiveRequest $request)
    {
        $member = auth('api')->user();

        $no = $request->no;
        $memo = '会员确认收货';
        $order = Order::where('no',$no)->firstOrFail();

        if ($order->member_id != $member->id) {
            throw new UnauthorizedException('没有操作权限');
        }

        if ($order->status != Order::STATUS_SHIPPED) {
            return responseFail('只有已发货的订单才能确认收货');
        }

        $orderSkus = $order->orderSkus;
        $order->status = Order::STATUS_RECEIVED;
        $order->save();

        OrderStatus::addStatus($order,$memo,$member->id);

        foreach ($orderSkus as $orderSku) {
            // 处理order_skus
            $orderSku->status = Order::STATUS_RECEIVED;
            $orderSku->save();

            OrderSkuStatus::addStatus($orderSku, $memo, $member->id);
        }

        // 处理佣金记录
        // ...

        return responseSuccess('确认收货成功');
    }

    /**
     * 取消订单
     * @param OrderCancelRequest $request
     * @return JsonResponse
     */
    public function cancel(OrderCancelRequest $request)
    {

        $member = auth('api')->user();

        $no = $request->no;

        $order = Order::where('no',$no)->firstOrFail();

        if ($order->member_id != $member->id) {
            throw new UnauthorizedException('没有操作权限');
        }

        $orderSkus = $order->orderSkus;
        $memo = '会员取消订单';

        if ($order->status > Order::STATUS_UNPAID) {
            //已支付
            $shipped = false;
            foreach ($orderSkus as $orderSku) {
                // 已发货不能取消
                if ($orderSku->status > Order::STATUS_CONFIRMED) {
                    $shipped = true;
                    break;
                }
            }
            if ($shipped) {
                return responseFail('订单不允许取消');
            }

            // 是否有现金或积分需要返还
            if ($order->total_amount > 0 || $order->total_amount_score > 0) {
                $order->status = Order::STATUS_REFUNDING;
                $order->save();

                OrderStatus::addStatus($order,'会员取消订单，退款中',$member->id);

                foreach ($orderSkus as $orderSku) {
                    // 处理order_skus
                    $orderSku->status = Order::STATUS_REFUNDING;
                    $orderSku->save();

                    OrderSkuStatus::addStatus($orderSku, $memo, $member->id);
                }

                // 全单退款，生成退款单
                $refundOrder = new RefundOrder();
                $refundOrder->member_id = $member->id;
                $refundOrder->order_id = $order->id;
                $refundOrder->no = RefundOrder::generateNo();
                $refundOrder->status = RefundOrder::STATUS_REFUNDING;
                $refundOrder->pay_type = $order->pay_type;
                $refundOrder->full_refund = true;
                $refundOrder->total_amount = $order->total_amount;
                $refundOrder->total_amount_score = $order->total_amount_score;
                $refundOrder->memo = $memo;
                $refundOrder->ip_address = $request->ip();
                $refundOrder->user_agent = $request->userAgent();
                $refundOrder->year = Carbon::now()->year;
                $refundOrder->month = Carbon::now()->month;
                $refundOrder->day = Carbon::now()->day;
                $refundOrder->hour = Carbon::now()->hour;
                $refundOrder->minute = Carbon::now()->minute;
                $refundOrder->save();

                RefundOrderStatus::addStatus($refundOrder,$memo,$member->id);
            }

            // 更新关联的分佣记录
            // ...
        }

        // 修改订单状态
        $order->status = Order::STATUS_CANCELLED;
        $order->save();

        // 记录订单状态
        OrderStatus::addStatus($order,$memo,$member->id);

        // 处理order_skus
        foreach ($orderSkus as $orderSku) {
            $orderSku->status = Order::STATUS_CANCELLED;
            $orderSku->save();

            OrderSkuStatus::addStatus($orderSku,$memo,$member->id);

            // 返还库存
            $sku = $orderSku->sku;
            $sku->num_stock += $orderSku->quantity;
            $sku->save();
        }

        return responseSuccess('取消订单成功');

    }

    /**
     * 快递查询
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function express(Request $request) {
        $member = auth('api')->user();

        $orderSkuId = $request->order_sku_id;

        $orderSku = OrderSku::findOrFail($orderSkuId);

        if ($orderSku->order->member_id != $member->id) {
            throw new UnauthorizedException('没有操作权限');
        }

        $express = Express::findOrFail($orderSku->express_id);

        $expressNo = $orderSku->express_no;

        $expressLib = new \App\Libraries\Express();
        $result = $expressLib->resultList($expressNo,$express->alias);

        $status = -1;
        $listArray = array();
        if (isset($result['data'])) {
            $dataList = $result['data'];
            $status = $result['status']; //-1 待查询 0 查询异常 1 暂无记录 2 在途中 3 派送中 4 已签收 5 用户拒签 6 疑难件 7 无效单 8 超时单 9 签收失败 10 退回

            foreach ($dataList as $dataRow) {
                array_push($listArray, array(
                    'time'=>$dataRow['time'],
                    'status'=>$dataRow['context']
                ));
            }
        }

        return responseSuccess('快递信息',[
            'order_no'=>$orderSku->order->no,
            'company'=>$express->name,
            'status'=>$status,
            'express_no'=>$expressNo,
            'list'=>$listArray
        ]);
    }

}
