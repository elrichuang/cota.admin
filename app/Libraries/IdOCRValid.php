<?php


namespace App\Libraries;


use Exception;
use GuzzleHttp\Client;

class IdOCRValid
{
    /**
     * @param string $idCardNo
     * @param string $name
     * @param string $image
     * @return mixed
     * @throws Exception
     */
    public function valid($idCardNo,$name,$image)
    {
        try{
            $params = array(
                'idcard'=>$idCardNo,
                'image'=>$image,
                'name'=>$name,
            );

            $client = new Client();
            $responseData = $client->request('POST',config('app.id_card_ocr_valid.apiUrl'),array(
                'form_params' => json_encode($params),
                'headers' => [
                    'Authorization' => 'APPCODE '.config('app.id_card_ocr_valid.appCode')
                ]
            ));

            $jsonArray = json_decode($responseData->getBody(),true);
            if (!$jsonArray)
            {
                throw new Exception('身份证验证接口出错');
            }

            $success = $jsonArray['success'];
            if (!$success) {
                throw new Exception('身份证识别错误');
            }

            $incorrect = $jsonArray['data']['incorrect'];
            switch ($incorrect)
            {
                case 100:
                    return true;
                    break;
                case 101:
                    throw new Exception('身份证号码姓名不一致');
                    break;
                case 102:
                    throw new Exception('库中无此号');
                    break;
                case 103:
                    throw new Exception('证件号码一致，照片比对时报错');
                    break;
                case 104:
                    throw new Exception('未进行证件号校验，数据检验报错');
                    break;
                case 106:
                    throw new Exception('身份核验成功，人脸识别系统异常');
                    break;
                case 107:
                    throw new Exception('照片质量不合格');
                    break;
                case 108:
                    throw new Exception('上传图片文件过大');
                    break;
                case 109:
                    throw new Exception('身份核验成功，库中无照片');
                    break;
                case 110:
                    throw new Exception('身份核验成功，特征提取失败');
                    break;
                case 111:
                    throw new Exception('身份核验成功，检测到多于一张人脸');
                    break;
                case 112:
                    throw new Exception('身份核验成功，图片不合法');
                    break;
                case 113:
                    throw new Exception('人像比对服务异常');
                    break;
                default:
                    throw new Exception('校验失败');
                    break;
            }
        }catch (Exception $exception) {
            throw $exception;
        }
    }
}
