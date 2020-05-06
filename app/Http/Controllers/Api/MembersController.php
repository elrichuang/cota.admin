<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\MemberBindRequest;
use App\Http\Requests\MemberLoginRequest;
use App\Http\Requests\MemberProfileUpdateRequest;
use App\Http\Requests\MemberRegisterRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MembersController extends Controller
{
    public function __construct()
    {
        $this->middleware('refresh.token:api',[
            'except'=>['login','store']
        ]);

        //$this->authorizeResource(User::class,'user');
    }

    /**
     * 注册
     *
     * @param MemberRegisterRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function store(MemberRegisterRequest $request)
    {
        //username,email,phone,code
        $type = config('app.member.registerType');
        $phone = $request->phone;
        $password = $request->password;
        $nickname = $request->username;
        $email = $request->email;
        $code = $request->code;
        $code_key = $request->code_key;

        $insertData = [
            'nickname' => '会员'.Str::random(8)
        ];
        if ($type == 'username')
        {
            $insertData['nickname'] = $nickname;
        }

        if ($type == 'email')
        {
            $insertData['email'] = $email;
        }

        if ($type == 'phone' || $type == 'code')
        {
            $insertData['phone'] = $phone;
        }

        if ($type == 'username' || $type == 'email' || $type == 'phone')
        {
            $insertData['password'] = bcrypt($password);
        }

        if ($type == 'code')
        {
            // 手机验证码登录
            $verifyData = Cache::get($code_key);
            if (!$verifyData) {
                abort(403, '验证码已失效');
            }

            if (!hash_equals($verifyData['code'], $code)) {
                // 返回401
                throw new AuthenticationException('验证码错误');
            }
        }

        $member = Member::create($insertData);

        return responseSuccess('注册成功',new MemberResource($member));
    }

    /**
     * 个人信息
     *
     * @param  Member  $member
     * @return JsonResponse
     */
    public function show(Member $member)
    {
        return responseSuccess('会员信息',new MemberResource($member));
    }

    /**
     * 修改会员
     *
     * @param MemberProfileUpdateRequest $request
     * @param Member $member
     * @return JsonResponse
     */
    public function update(MemberProfileUpdateRequest $request, Member $member)
    {
        try {
            $member->name = $request->name;
            $member->nickname = $request->nickname;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->avatar = $request->avatar;
            $member->sex = $request->sex;
            $member->save();

            return responseSuccess('更新成功', new MemberResource($member));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * 手机绑定
     * @param MemberBindRequest $request
     * @param Member $member
     * @return JsonResponse
     */
    public function bind(MemberBindRequest $request, Member $member)
    {
        try {
            $phone = $request->phone;
            $code = $request->code;
            $code_key = $request->code_key;

            $codes = Cache::get($code_key);
            if (!$codes || $codes['phone'] != $phone || $codes['code'] != $code)
            {
                // 清除验证码
                Cache::forget($code_key);
                throw new AuthenticationException('手机号或验证码错误');
            }

            // 清除验证码
            Cache::forget($code_key);

            $member->phone = $phone;
            $member->save();

            return responseSuccess('绑定成功',new MemberResource($member));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    public function refreshToken()
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    /**
     * @param MemberLoginRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function login(MemberLoginRequest $request)
    {
        //username,email,phone,code
        $type = config('app.member.loginType');
        $phone = $request->phone;
        $code = $request->code;
        $code_key = $request->code_key;

        if ($type == 'username')
        {
            $credentials['nickname'] = $request->username;
        }

        if ($type == 'email')
        {
            $credentials['email'] = $request->email;
        }

        if ($type == 'phone' || $type == 'code')
        {
            $credentials['phone'] = $phone;
        }

        if ($type == 'username' || $type == 'email' || $type == 'phone')
        {
            $credentials['password'] = $request->password;
        }

        if ($type != 'code')
        {
            if (!$token = auth('api')->attempt($credentials)) {
                throw new AuthenticationException('用户名或密码错误');
            }
        }else {
            // 手机验证码登录
            $codes = Cache::get($code_key);
            if (!$codes || $codes['phone'] != $phone || $codes['code'] != $code)
            {
                // 清除验证码
                Cache::forget($code_key);
                throw new AuthenticationException('手机号或验证码错误');
            }

            // 清除验证码
            Cache::forget($code_key);

            $member = Member::where('phone',$phone)->first();
            if (!$member)
            {
                throw new AuthenticationException('登录失败');
            }

            $token = auth('api')->login($member);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth('api')->logout();
        //return responseSuccess('退出登录',[],0,204);
        return responseSuccess('退出登录');
    }

    protected function respondWithToken($token)
    {
        return responseSuccess('登录成功',[
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ],0,201);
    }
}
