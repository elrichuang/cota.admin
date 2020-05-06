<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.Str::random(15);

        $captcha = $captchaBuilder->build(153, 38);
        $expiredAt = now()->addMinutes(2);
        Cache::put($key, ['ip' => $request->ip(), 'code' => strtolower($captcha->getPhrase())], $expiredAt);
        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return responseSuccess('图形验证码', $result);
    }
}
