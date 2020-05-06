<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\VerificationCodeRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;

class VerificationCodesController extends Controller
{
    /**
     * 发送手机验证码
     * @param VerificationCodeRequest $request
     * @param EasySms $easySms
     * @return JsonResponse
     * @throws AuthenticationException
     * @throws InvalidArgumentException
     */
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        //图形验证码
        $captchaData = Cache::get($request->captcha_key);

        if (!$captchaData) {
            abort(403, '图片验证码已失效');
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 验证错误就清除缓存
            Cache::forget($request->captcha_key);
            throw new AuthenticationException('验证码错误');
        }

        // 清除图形验证码
        Cache::forget($request->captcha_key);

        $phone = $request->phone;

        if (app()->environment('production'))
        {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            try {
                $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data' => [
                        'code' => $code,
                        'product' => '猩云平台'
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }else
        {
            $code = '1234';
        }

        $key = 'verificationCode_'.Str::random(15);
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码 5 分钟过期。
        Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return responseSuccess('验证码发送成功',[
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ],0,201);
    }
}
