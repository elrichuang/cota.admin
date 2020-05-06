<?php

namespace App\Http\Controllers\Api;

use App\libraries\TgPosp;
use App\Models\MerchantOrder;
use App\Models\Order;
use App\Models\OrderSku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MerchantOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('refresh.token:api');

        //$this->authorizeResource(User::class,'user');
    }
    /**
     * @param MerchantOrder $merchantOrder
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInvoice(MerchantOrder $merchantOrder) {

        if ($merchantOrder->total_amount <= 0) {
            return responseFail('订单实付金额必须大于 0');
        }

        $merchant = $merchantOrder->merchant;

        if (!$merchant->has_invoice) {
            return responseFail('商家未开通自助开票');
        }

        // 是否都已收货
        $orderSkus = OrderSku::where([
            'order_id' => $merchantOrder->order_id,
            'merchant_id' => $merchantOrder->merchant_id
        ])->get();

        if (!$orderSkus) {
            return responseFail('找不到订单商品信息');
        }

        foreach ($orderSkus as $orderSku) {
            if ($orderSku->status != Order::STATUS_RECEIVED) {
                return responseFail('有商品未收货，不能开发票');
            }
        }

        if ($merchantOrder->invoice_url) {
            return responseSuccess('电子发票',[
                'url' => $merchantOrder->invoice_url
            ]);
        }

        $itemList = [];

        foreach ($orderSkus as $orderSku) {
            $spu = $orderSku->spu;
            $itemList[] = [
                'itemName' => $spu->name,
                'taxRateValue' => $spu->tax_rate_value,
                'taxClassificationCode' => $spu->tax_classification_code,
                'unitPrice' => $orderSku->sku_price,
                'quantity' => $orderSku->quantity,
                'invoiceItemAmount' => $orderSku->total_amount
            ];
        }

        $tgposp = new TgPosp();
        $result = $tgposp->getInvoiceQrCodeApi($merchantOrder->total_amount,$itemList);

        // 成功，更新发票连接
        if ($result['status'] == TgPosp::STATUS_SUCCESS) {
            $invoice_url = Arr::get($result,'qrCodeUrl');
            $merchantOrder->invoice_url = $invoice_url;
            $merchantOrder->save();

            return responseSuccess('电子发票开票链接',[
                'url' => $invoice_url
            ]);
        }else {
            return responseFail($result['message']);
        }
    }
}
