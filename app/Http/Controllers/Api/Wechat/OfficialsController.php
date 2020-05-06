<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Handlers\Wechat\EventMessageHandler;
use App\Handlers\Wechat\ImageMessageHandler;
use App\Handlers\Wechat\LocationMessageHandler;
use App\Handlers\Wechat\MessageLogHandler;
use App\Handlers\Wechat\TextMessageHandler;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Transfer;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * 处理公众号
 * Class OfficialsController
 * @package App\Http\Controllers\Api
 */
class OfficialsController extends Controller
{
    public function serve()
    {
        $config = [
            'app_id' => config('wechat.official.appId'),
            'secret' => config('wechat.official.appSecret'),
            'token' => config('wechat.official.token'),
            'aes_key'=> config('wechat.official.aesKey'),
            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
             * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
             */
            'response_type' => 'array'
        ];

        $app = Factory::officialAccount($config);
        $app->server->push(MessageLogHandler::class); //记录日志
        $app->server->push(TextMessageHandler::class,Message::TEXT); //处理文本信息
        $app->server->push(ImageMessageHandler::class,Message::IMAGE); //处理图片信息
        $app->server->push(EventMessageHandler::class,Message::EVENT); //处理事件信息
        $app->server->push(LocationMessageHandler::class,Message::LOCATION); //处理地理位置信息
        //客服消息转发
        $app->server->push(function($message) {
            return new Transfer();
        });

        return $app->server->serve();
    }

    /**
     * 获取微信公众号授权地址
     * @return JsonResponse
     */
    public function oauth()
    {
        $config = [
            'app_id' => config('wechat.official.appId'),
            'secret' => config('wechat.official.appSecret'),
            'token' => config('wechat.official.token'),
            'aes_key'=> config('wechat.official.aesKey'),
            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
             * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
             */
            'response_type' => 'array',
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => config('wechat.official.redirectUri'),
            ],
        ];

        $app = Factory::officialAccount($config);

        $url = $app->oauth->redirect()->getTargetUrl();

        return responseSuccess('微信公众号授权回调地址',['url'=>$url]);
    }

    /**
     * 微信公众号授权回调
     * @param Request $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function oauthCallback(Request $request)
    {
        $config = [
            'app_id' => config('wechat.official.appId'),
            'secret' => config('wechat.official.appSecret'),
            'token' => config('wechat.official.token'),
            'aes_key'=> config('wechat.official.aesKey'),
            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
             * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
             */
            'response_type' => 'array',
            'oauth' => [
                'scopes'   => ['snsapi_userinfo'],
                'callback' => config('wechat.official.redirectUri'),
            ],
        ];

        $app = Factory::officialAccount($config);

        // 获取 OAuth 授权结果用户信息
        $user = $app->oauth->user();

        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用

        $member = null;
        $originalArray = $user->getOriginal();
        $unionid = $originalArray['unionid'] ? $originalArray['unionid'] : null;

        if ($unionid) {
            $member = Member::where('weixin_unionid', $unionid)->first();
        } else {
            $member = Member::where('weixin_openid', $user->getId())->first();
        }

        // 没有用户，默认创建一个用户
        if (!$member) {
            $member = Member::create([
                'nickname' => $user->getNickname(),
                'avatar' => $user->getAvatar(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'sex' => $originalArray['sex'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'year' => Carbon::now()->year,
                'month' => Carbon::now()->month,
                'day' => Carbon::now()->day,
                'hour' => Carbon::now()->hour,
                'minute' => Carbon::now()->minute,
                'weixin_appid' => config('wechat.official.appId'),
                'weixin_openid' => $user->getId(),
                'weixin_unionid' => $unionid,
            ]);
        }

        if (!$member)
        {
            throw new AuthenticationException('注册新用户失败');
        }

        $token= auth('api')->login($member);

        return responseSuccess('微信公众号授权回调',[
            'member' => new MemberResource($member),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * 微信公众号JSSDK
     * @param Request $request
     * @return JsonResponse
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function jssdk(Request $request)
    {
        $debug = false;
        if($request->debug !== null)
        {
            $debug = boolval($debug);
        }
        $apis = [
            'updateAppMessageShareData',
            'updateTimelineShareData'
        ];
        $postApis = explode(',',$request->apis);
        if ($postApis) {
            foreach ($postApis as $postApi) {
                if (!in_array($postApi,$apis)) {
                    $apis[] = $postApi;
                }
            }
        }
        $url = $request->url;

        $config = [
            'app_id' => config('wechat.official.appId'),
            'secret' => config('wechat.official.appSecret'),
            'token' => config('wechat.official.token'),
            'aes_key'=> config('wechat.official.aesKey'),
            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
             * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
             */
            'response_type' => 'array'
        ];

        $app = Factory::officialAccount($config);
        if ($url)
        {
            $app->jssdk->setUrl($url);
        }
        $configStr = $app->jssdk->buildConfig($apis, $debug,false,false);

        return responseSuccess('JSSDK',[
            'config' => $configStr
        ]);
    }
}
