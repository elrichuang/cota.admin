<?php
namespace App\Libraries;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Express {

    /**
     * 快递查询结果
     * @param string $no
     * @param string $alias
     * @return mixed
     * @throws Exception
     */
	public function resultList($no, $alias)
	{
	    $params = array(
	        'com'=>$alias,
	        'nu'=>$no,
	    );

	    $cacheKey = 'express_'.$alias.'_'.$no;
	    $result = Cache::get($cacheKey);
	    if (!$result) {
            $client = new Client();
            $responseData = $client->request('GET',config('app.express.apiUrl'),array(
                'query' => $params,
                'headers' => [
                    'Authorization' => 'APPCODE '.config('app.express.appCode')
                ]
            ));
            /* 返回结果说明
            {
                "showapi_res_code": 0,//showapi平台返回码,0为成功,其他为失败
                "showapi_res_error": "",//showapi平台返回的错误信息
                "showapi_res_body": {
                    "mailNo": "968018776110",//快递单号
                    "update": 1466926312666,//数据最后查询的时间
                    "updateStr": "2016-06-26 15:31:52",//数据最后更新的时间
                    "ret_code": 0,//接口调用是否成功,0为成功,其他为失败
                    "flag": true,//物流信息是否获取成功
                    "status": 4,-1 待查询 0 查询异常 1 暂无记录 2 在途中 3 派送中 4 已签收 5 用户拒签 6 疑难件 7 无效单
             8 超时单 9 签收失败 10 退回
                    "tel": "400-889-5543",//快递公司电话
                    "expSpellName": "shentong",//快递字母简称
                    "data": [//具体快递路径信息
                        {
                            "time": "2016-06-26 12:26",
                            "context": "已签收,签收人是:【本人】"
                        },
                        {
                            "time": "2016-06-25 15:31",
                            "context": "【陕西陇县公司】的派件员【西城业务员】正在派件"
                        },
                        ...
                    ],
                    "possibleExpList": [//当auto查询失败的时候,返回此信息,成功时不返回
                                        //用户表示该单号可能属于那些快递物流公司
                            {
                                "simpleName": "shunfeng",//快递公司简称
                                     "expName": "顺丰速运"
                             }
                     ],
                    "expTextName": "申通快递"//快递公司名
                }
            }
            */

    	    $jsonArray = json_decode($responseData->getBody(),true);
    	    if (!$jsonArray)
    	    {
    	        throw new Exception('快递接口出错');
    	    }

    	    $resCode = $jsonArray['showapi_res_code'];
    	    if ($resCode != 0) {
                throw new Exception('快递接口出错res_code='.$resCode);
    	    }else {
                //成功，缓存2小时
                $resBody = $jsonArray['showapi_res_body'];
                $ttl = 7200;

                if ($resBody['status'] == 4) {
                    $ttl = 3600*24*7;
                }

                Cache::put($cacheKey,$resBody,$ttl);

                return $resBody;
    	    }
	    }

	    return $result;
	}

}
