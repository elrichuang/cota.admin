<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use EasyWeChat\Factory;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class MiniappsController extends Controller
{
    /**
     * 小程序授权
     * @param Request $request
     * @return JsonResponse
     * @throws AuthenticationException
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

        $app->oauth->session($code);
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

        return responseSuccess('微信小程序登录',[
            'member' => new MemberResource($member),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
