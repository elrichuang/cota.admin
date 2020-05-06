<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Http\Controllers\Api\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentsController extends Controller
{
    public function notify()
    {
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

        return $app->handlePaidNotify(function ($message, $fail) {
            // 记录日志
            Log::info('微信支付回调',$message);

            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = Order::where('no',$message['out_trade_no'])->firstOrFail();

            if (!$order || $order->status == Order::STATUS_PAID) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {
                    Order::payOk($order,Order::PAY_TYPE_WEIXIN, $message['transaction_id']);
                    // 用户支付失败
                } elseif ($message['result_code'] === 'FAIL') {
                    Order::payFail($order,Order::PAY_TYPE_WEIXIN);
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });
    }

    public function refundNotify()
    {
        $config = [
            'mch_id' => config('wechat.pay.isvMchId'),
            'key' => config('wechat.pay.isvKey'),
            'cert_path'=> config('wechat.pay.isvCertPath'),
            'key_path'=> config('wechat.pay.isvKeyPath'),
            'notify_url'> config('wechat.pay.notifyUrl'),
            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
             * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
             */
            'response_type' => 'array'
        ];

        $app = Factory::payment($config);

        $app->setSubMerchant(config('wechat.pay.mchId'), config('wechat.pay.appId'));

        return $app->handleRefundedNotify(function ($message, $fail) {
            // 记录日志


            // 其中 $message['req_info'] 获取到的是加密信息
            // $reqInfo 为 message['req_info'] 解密后的信息
            // 你的业务逻辑...
            return true; // 返回 true 告诉微信“我已处理完成”
            // 或返回错误原因 $fail('参数格式校验错误');
        });
    }
}
