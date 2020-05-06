<?php

return [
    'account' => env('TGPOSP_ACCOUNT',''),
    'key'=>env('TGPOSP_KEY',''),
    'url'=>[
        'microPay' => env('TGPOSP_URL_MICRO_PAY',''),
        'wxJsPay' => env('TGPOSP_URL_WX_JSPAY',''),
        'allQrcodePay' => env('TGPOSP_URL_ALL_QRCODE_PAY',''),
        'reverse'=> env('TGPOSP_URL_REVERSE',''),
        'reverseV2'=>env('TGPOSP_URL_REVERSE_V2',''),
        'reverseImmediately'=>env('TGPOSP_URL_REVERSE_IMMEDIATELY',''),
        'orderQuery'=>env('TGPOSP_URL_ORDER_QUERY',''),
        'refundQuery'=>env('TGPOSP_URL_REFUND_QUERY',''),
        'unifiedOrder'=>env('TGPOSP_URL_UNIFIED_ORDER',''),
        'closeTradeOrder'=>env('TGPOSP_URL_CLOSE_TRADE_ORDER',''),
        'aliSmile'=>env('TGPOSP_URL_ALI_SMILE',''),
        'orderIdQuery'=>env('TGPOSP_URL_ORDER_ID_QUERY',''),
        'authCode2openId'=>env('TGPOSP_URL_AUTH_CODE2_OPENID',''),
        'getInvoiceQrCodeApi'=>env('TGPOSP_URL_GET_INVOICE_QRCODE_API',''),
    ],
];
