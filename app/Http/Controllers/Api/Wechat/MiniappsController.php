<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Http\Controllers\Api\Controller;
use App\Models\Member;
use EasyWeChat\Factory;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MiniappsController extends Controller
{
    /**
     * 小程序授权
     * @param Request $request
     * @return JsonResponse
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function oauth(Request $request)
    {
        $code = $request->get('code');

        $config = [
            'app_id' => config('wechat.miniapp.appId'),
            'secret' => config('wechat.miniapp.appSecret'),
            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
             * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
             */
            'response_type' => 'array'
        ];

        $app = Factory::miniProgram($config);

        $sessionData = $app->auth->session($code);
        if(isset($sessionData['errcode']) && $sessionData['errcode'] != 0) {
            return responseFail($sessionData['errmsg']);
        }

        $mySessionKey = md5(randomFromDev(128));

        $unionid = null;
        if (isset($sessionData['unionid'])) {
            $unionid = $sessionData['unionid'];
        }

        $cacheData = [
            'session_key'=>$sessionData['session_key'],
            'openid'=>$sessionData['openid'],
            'unionid'=>$unionid
        ];

        // 缓存1周。
        Cache::put($mySessionKey, $cacheData, 3600 * 24 * 7);

        return responseSuccess('微信小程序授权',[
            'session'=>$mySessionKey,
            'openid'=>$sessionData['openid']
        ]);
    }

    /**
     * 删除session
     * @param Request $request
     * @return JsonResponse
     */
    public function delSession(Request $request)
    {
        try {
            $sessionKey = $request->get('session');

            if(Cache::has($sessionKey))
            {
                Cache::forget($sessionKey);
            }

            return responseSuccess('微信小程序session删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * 保存用户信息
     * @param Request $request
     * @return JsonResponse
     */
    public function saveUserInfo(Request $request)
    {
        try {
            $encryptedData = $request->get('encrypted_data');
            $iv = $request->get('iv');
            $sessionKey = $request->get('session');
            if(!Cache::has($sessionKey))
            {
                return responseFail('缺少微信session');
            }

            $sessionData = Cache::get($sessionKey);

            $config = [
                'app_id' => config('wechat.miniapp.appId'),
                'secret' => config('wechat.miniapp.appSecret'),
                /**
                 * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
                 * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
                 */
                'response_type' => 'array'
            ];

            $app = Factory::miniProgram($config);

            $decryptedData = $app->encryptor->decryptData($sessionData['session_key'], $iv, $encryptedData);

            if (!$decryptedData) {
                return responseFail('数据解密失败');
            }

            $nickname = $decryptedData['nickName'];
            $avatar = $decryptedData['avatarUrl'];
            $gender = $decryptedData['gender'];
            $province = $decryptedData['province'];
            $city = $decryptedData['city'];
            $country = $decryptedData['country'];
            $openid = $decryptedData['openId'];

            $unionid = null;
            if (isset($decryptedData['unionId'])){
                $unionid = $decryptedData['unionId'];
            }

            Cache::forever($openid,[
                'openid' => $openid,
                'unionid' => $unionid,
                'nickname'=>$nickname,
                'headimgurl' => $avatar,
                'sex' => $gender
            ]);

            // 是否已注册会员
            $member = Member::where('weixin_openid',$openid)->first();
            if ($member) {
                // 更新
                $member->nickname = $nickname;
                $member->sex = $gender;
                $member->province = $province;
                $member->city = $city;
                $member->country = $country;
                if ($unionid) {
                    $member->unionid = $unionid;
                }

                // 头像
                if (strpos($avatar, 'http://') === 0 || strpos($avatar, 'https://') === 0) {
                    $client = new Client(['verify' => false]);  //忽略SSL错误
                    $response = $client->get($avatar);  //保存远程url到文件
                    if ($response->getStatusCode() == 200) {
                        $avatar = md5($avatar) . '.jpg';
                        $disk = Storage::disk('oss');
                        // 上传
                        $disk->put($avatar, $response->getBody());
                        // 获取URL
                        $avatar = $disk->getUrl($avatar);
                        $member->avatar = $avatar;
                    }
                }

                $member->save();
            }

            return responseSuccess('保存用户信息成功');

        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * 获取用户微信信息
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserInfo(Request $request)
    {
        try {
            $sessionKey = $request->get('session');
            if(!Cache::has($sessionKey))
            {
                return responseFail('缺少微信session');
            }

            $sessionData = Cache::get($sessionKey);

            $openid = $sessionData['openid'];

            if(!Cache::has($openid))
            {
                return responseFail('请重新授权登录');
            }

            $weixinData = Cache::get($openid);

            return responseSuccess('获取用户信息成功',$weixinData);
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
