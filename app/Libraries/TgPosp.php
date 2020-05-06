<?php

namespace App\libraries;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class TgPosp
 * 通莞金服
 * 所有价格单位元
 * @package App\libraries
 */
class TgPosp
{
    const STATUS_SUCCESS = 100;
    const STATUS_FAILED = 101;

    const STATE_SUCCESS = 0; // 成功
    const STATE_FAILED = 1; // 失败
    const STATE_REVERSE = 2; // 已撤销
    const STATE_WAITING_FOR_PAY = 4; // 待支付
    const STATE_REFUNDED = 5; // 已退款申请成功
    const STATE_REFUND_PART = 6; // 已转入部分退款申请成功

    public $account = '';
    public $key = '';

    protected $client;

    /**
     * TgPosp constructor.
     * @param string $account
     * @param string $key
     */
    public function __construct($account = null,$key = null)
    {
        if (!$account || !$key) {
            $this->account = config('tgposp.account');
            $this->key = config('tgposp.key');
        }else {
            $this->account = $account;
            $this->key = $key;
        }

        $this->client = new Client();
    }

    /**
     * 扫码支付接口
     * 商户使用终端机具或者扫码枪等扫描消费者付款码进行支付，此种属于被扫模式。
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function micropay(array $params) {
        try {
            // $order_no,$payMoney,$barcode,$terminal_params = '',$body = '',$lowCashier = '',$scene = '',$pay_type = '',$detail='',$store_id = ''
            $json = array(
                'account' => $this->account,
                'payMoney' => Arr::get($params,'payMoney'),
                'lowOrderId' => Arr::get($params,'orderNo'),
                'barcode' => Arr::get($params,'barcode'),
                'body' => Arr::get($params,'body'),
                'lowCashier' => Arr::get($params,'lowCashier'),
                'scene' => Arr::get($params,'scene'),
                'payType' => Arr::get($params,'pay_type'),
                'terminalParams'=>Arr::get($params,'terminalParams'),
                'detail' => Arr::get($params,'detail'),
                'storeId' => Arr::get($params,'storeId')
            );

            $sign = $this->sign($json);
            $json['sign'] = $sign;

            $options = [
                'json' => $json,
            ];
            $response_data = $this->client->post(config('tgposp.url.microPay'), $options);
            $response_data = $response_data->getBody()->getContents();

//            $response_data = '{"sign":"723D7BFED993F063337881E1DEE166F6","message":"支付成功","payMoney":0.01,"status":100,"state":"0","upOrderId":"91195229707365257216","payType":"0","account":"13974747474","payTime":"2019-11-15 14:39:43","openId":"oNTN20TSX1HhTRNRTMwGElW-8JXA","lowOrderId":"25337020201911151439227373"}';
            Log::info('通莞扫码支付接口返回',['data' => $response_data]);
            return json_decode($response_data,true);
        }catch (GuzzleException $exception) {
            throw $exception;
        }catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 整单退款，消费撤销
     * 针对支付成功的交易，可以调用此接口撤销原交易。支付完成时间超过四分钟请调用退款接口
     * @param string $upOrderId
     * @param string $lowOrderId
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function reverse($upOrderId,$lowOrderId) {
        try {
            $json = array(
                'account' => $this->account,
                'upOrderId' => $upOrderId,
                'lowOrderId' => $lowOrderId,
            );

            $sign = $this->sign($json);
            $json['sign'] = $sign;

            $options = [
                'json' => $json,
            ];
            $response_data = $this->client->post(config('tgposp.url.reverse'), $options);
            $response_data = $response_data->getBody()->getContents();

            Log::info('通莞订单撤销接口返回',['data' => $response_data]);
            return json_decode($response_data,true);
        }catch (GuzzleException $exception) {
            throw $exception;
        }catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 消费撤销，整单冲正
     * 立即冲正，不论支付是否成功，只要支付发起超过 15 秒，并且支付完成不能超过 4 分钟，即可调用该接口发起冲正。
     * @param string $upOrderId
     * @param string $lowOrderId
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function reverseImmediately($upOrderId,$lowOrderId) {
        try {
            $json = array(
                'account' => $this->account,
                'upOrderId' => $upOrderId,
                'lowOrderId' => $lowOrderId,
            );

            $sign = $this->sign($json);
            $json['sign'] = $sign;

            $options = [
                'json' => $json,
            ];
            $response_data = $this->client->post(config('tgposp.url.reverseImmediately'), $options);
            $response_data = $response_data->getBody()->getContents();

            Log::info('通莞订单立即冲正接口返回',['data'=>$response_data]);

            return json_decode($response_data,true);
        }catch (GuzzleException $exception) {
            throw $exception;
        }catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 部分退款，消费撤销
     * 针对支付成功的交易，可以调用此接口撤销原交易。支付完成时间超过四分钟请调用退款接口
     * @param $upOrderId
     * @param $lowRefundNo
     * @param $refundAmount
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function reverse2($upOrderId,$lowRefundNo,$refundAmount) {
        try {
            $json = array(
                'account' => $this->account,
                'upOrderId' => $upOrderId,
                'lowRefundNo' => $lowRefundNo,
                'refundMoney'=> $refundAmount
            );

            $sign = $this->sign($json);
            $json['sign'] = $sign;

            $options = [
                'json' => $json,
            ];
            $response_data = $this->client->post(config('tgposp.url.reverseV2'), $options);
            $response_data = $response_data->getBody()->getContents();

            Log::info('通莞部分退款接口返回',['data' => $response_data]);

            return json_decode($response_data,true);
        }catch (GuzzleException $exception) {
            throw $exception;
        }catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 订单查询
     * 用订单号查询订单详情，此接口中下游订单号 lowOrderId 与通莞金服订单号 upOrderId 二选一。
     * @param string $lowOrderId
     * @param string $upOrderId
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function orderQuery($lowOrderId,$upOrderId = '') {
        try {
            $json = array(
                'account' => $this->account,
            );

            if ($upOrderId) {
                $json['upOrderId'] = $upOrderId;
            }

            if ($lowOrderId) {
                $json['lowOrderId'] = $lowOrderId;
            }

            $sign = $this->sign($json);
            $json['sign'] = $sign;

            $options = [
                'json' => $json,
            ];
            $response_data = $this->client->post(config('tgposp.url.orderQuery'), $options);
            $response_data = $response_data->getBody()->getContents();

            Log::info('通莞订单查询接口返回',['data'=>$response_data]);

            return json_decode($response_data,true);
        }catch (GuzzleException $exception) {
            throw $exception;
        }catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 退款查询
     * 根据商户退款订单号查询退款订单是否成功
     * @param string $refundNo
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function refundQuery($refundNo) {
        try {
            $json = array(
                'account' => $this->account,
                'refund_no' => $refundNo
            );

            $sign = $this->sign($json);
            $json['sign'] = $sign;

            $options = [
                'json' => $json,
            ];
            $response_data = $this->client->post(config('tgposp.url.refundQuery'), $options);
            $response_data = $response_data->getBody()->getContents();

            Log::info('通莞退款查询接口返回',['data'=>$response_data]);

            return json_decode($response_data,true);
        }catch (GuzzleException $exception) {
            throw $exception;
        }catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 小程序或自有公众号时使用
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function wxJspay(array $params) {
        try {
            //$low_order_id,$total_amount,$openid,$notify_url,$body,$appid,$attach = '',$is_minipg = '1' ,$return_url = '',$store_id = ''
            $json = array(
                'account' => $this->account,
                'payMoney' => Arr::get($params,'payMoney'),
                'lowOrderId' => Arr::get($params,'lowOrderId'),
                'body' => Arr::get($params,'body'),
                'isMinipg' => Arr::get($params,'isMinipg'),
                'notifyUrl' => Arr::get($params,'notifyUrl'),
                'returnUrl' => Arr::get($params,'returnUrl'),
                'openId' => Arr::get($params,'openId'),
                'appId' => Arr::get($params,'appId'),
                'attach' => Arr::get($params,'attach'),
                'storeId' => Arr::get($params,'storeId')
            );

            $sign = $this->sign($json);
            $json['sign'] = $sign;

            $options = [
                'json' => $json,
            ];

            Log::info('通莞微信小程序支付接口发起',$json);

            $response_data = $this->client->post(config('tgposp.url.wxJsPay'), $options);
            $response_data = $response_data->getBody()->getContents();

            Log::info('通莞微信小程序支付接口返回',['data'=>$response_data]);

            return json_decode($response_data,true);
        }catch (GuzzleException $exception) {
            throw $exception;
        }catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 没有自己公众号时使用
     * @param array $params
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function allQrcodePay(array $params) {
        try {
            $json = array(
                'account' => $this->account,
                'payMoney' => Arr::get($params,'payMoney'),
                'lowOrderId' => Arr::get($params,'lowOrderId'),
                'body' => Arr::get($params,'body'),
                'attach' => Arr::get($params,'attach'),
                'lowCashier'=>Arr::get($params,'lowCashier'),
                'orderTimeOut'=>Arr::get($params,'orderTimeOut'),
                'notifyUrl' => Arr::get($params,'notifyUrl'),
                'returnUrl' => Arr::get($params,'returnUrl')
            );

            $sign = $this->sign($json);
            $json['sign'] = $sign;

            Log::info('通莞微信公众号支付接口发起',$json);

            $options = [
                'json' => $json,
            ];
            $response_data = $this->client->post(config('tgposp.url.allQrcodePay'), $options);
            $response_data = $response_data->getBody()->getContents();

            Log::info('通莞微信公众号支付接口返回',['data'=>$response_data]);

            return json_decode($response_data,true);
        }catch (GuzzleException $exception) {
            throw $exception;
        }catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * 电子发票开票链接获取接口
     * @param $totalAmount
     * @param $itemList
     * @param string $orderId
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function getInvoiceQrCodeApi($totalAmount,$itemList,$orderId = '') {
        try {
            $nonce_str = Str::random();

            $item_list_str = "[";
            $item_str_array = array();
            foreach ($itemList as $itemEntity) {
                //构建detail
                $detail_value = "{".
                    "\"invoiceItemAmount\":\"{$itemEntity['invoiceItemAmount']}\",".
                    "\"itemName\":\"{$itemEntity['itemName']}\",".
                    "\"quantity\":\"{$itemEntity['quantity']}\",".
                    "\"taxClassificationCode\":\"{$itemEntity['taxClassificationCode']}\",".
                    "\"taxRateValue\":\"{$itemEntity['taxRateValue']}\",".
                    "\"unitPrice\":\"{$itemEntity['unitPrice']}\"".
                    "}";
                array_push($item_str_array, $detail_value);
            }
            $all_item_str = implode(',', $item_str_array);
            $item_list_str .= $all_item_str;
            $item_list_str .="]";

            $json = array(
                'account' => $this->account,
                'payTime' => Carbon::now()->toDateTimeString(),
                'payMoney' => strval($totalAmount),
                'itemList' => $item_list_str,
                'nonceStr' => $nonce_str
            );
            if ($orderId) {
                $json['orderId'] = $orderId;
            }

            $sign = $this->sign($json);
            $json['sign'] = $sign;

            Log::info('通莞微信电子发票接口发起',$json);

            $options = [
                'json' => $json,
            ];
            $response_data = $this->client->post(config('tgposp.url.getInvoiceQrCodeApi'), $options);
            $response_data = $response_data->getBody()->getContents();

            Log::info('通莞微信电子发票接口返回',['data'=>$response_data]);
            return json_decode($response_data,true);
        }catch (GuzzleException $exception) {
            throw $exception;
        }catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param array $params
     * @return bool
     * @throws Exception
     * 验证参数签名
     */
    public function validParams($params) {
        if (!isset($params['sign'])) {
            throw new \Exception('缺少签名');
        }

        $old_sign = $params['sign'];

        $new_sign = $this->sign($params);

        if ($old_sign != $new_sign) {
            return false;
        }

        return true;
    }
    /**
     * @param array $params
     * @return string
     * 参数签名
     *
     * 3.1 签名 sign 算法
     * 所有参数字段都需要参与签名(字段值为 null、空值的字段不参与签名，sign 字段也不参与签名)，按照字母 ascii 升序并以&符号拼接后再拼接上通莞金服下发的 key，进行 MD5 加密再转成大写字符串。
     * 空值不参与签名
     * 例:某渠道 key=123qwe,某接口有 4 个参数都参与签名，ac=xxx，ab=xxx，c=xxx，b=xxx，按照 ascii 升序拼接后 为:
     * ab=xxx&ac=xxx&b=xxx&c=xxx
     * 再拼接 key
     * ab=xxx&ac=xxx&b=xxx&c=xxx&key=123qwe 最后对上述字符串进行 MD5 操作后转成大写即得到签名 sign
     */
    private function sign($params) {
        //删除sign不参与签名
        if (isset($params['sign'])) {
            unset($params['sign']);
        }

        //参数排序
        ksort($params);

        Log::info('通莞签名前数组',$params);

        $sign_str_array = array();
        foreach ($params as $key => $value) {
            //空值不参与签名
            if(is_array($value)) {
                array_push($sign_str_array, $key.'='.json_encode($value,JSON_UNESCAPED_UNICODE));
            }else {
                if (!is_null($value) && $value !== '')
                {
                    array_push($sign_str_array, $key.'='.$value);
                }
            }
        }

        $sign_str_array[] = 'key='.$this->key;

        $sign_str = implode('&', $sign_str_array);

        Log::info('通莞签名字符串',['sign'=>$sign_str]);

        return strtoupper(md5($sign_str));
    }
}
