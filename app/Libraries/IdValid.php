<?php


namespace App\Libraries;


use Exception;
use GuzzleHttp\Client;

class IdValid
{
    /**
     * @param $name string 姓名
     * @param $id_card_no string 身份证号
     * @return bool
     * @throws Exception
     */
    public function valid($name, $id_card_no)
    {
        try{
            $params = array(
                'idcard'=>$id_card_no,
                'name'=>$name,
            );

            $client = new Client();
            $responseData = $client->request('POST',config('app.id_card_valid.apiUrl'),array(
                'form_params' => $params,
                'headers' => [
                    'Authorization' => 'APPCODE '.config('app.id_card_valid.appCode')
                ]
            ));

            $jsonArray = json_decode($responseData->getBody(),true);
            if (!$jsonArray)
            {
                throw new Exception('身份证验证接口出错');
            }

            $resCode = $jsonArray['code'];
            if ($resCode != 10000) {
                throw new Exception($jsonArray['message']);
            }

            if (isset($jsonArray['data']['result'])) {
                if ($jsonArray['data']['result'] == 2) {
                    throw new Exception('身份证验证不通过');
                }elseif ($jsonArray['data']['result'] == 3) {
                    throw new Exception('身份证验证异常');
                }elseif ($jsonArray['data']['result'] == 1) {
                    return true;
                }
            }

            throw new Exception('身份证验证异常');
        }catch (Exception $exception) {
            throw $exception;
        }
    }
}
