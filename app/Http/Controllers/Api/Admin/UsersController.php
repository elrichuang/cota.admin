<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\UserDestroyManyRequest;
use App\Http\Requests\Admin\UserProfileRequest;
use App\Http\Requests\Admin\UserLoginRequest;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Resources\UserLogResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserLog;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    protected $modelClass = 'User';

    public function __construct()
    {
        $this->middleware('refresh.token:admin',[
            'except'=>['login']
        ]);

        //$this->authorizeResource(User::class,'user');
    }

    public function login(UserLoginRequest $request)
    {
        try {
            //图形验证码
            $captchaData = Cache::get($request->captcha_key);
            if (!$captchaData) {
                return responseFail('图片验证码已失效');
            }

            if ($request->ip() != $captchaData['ip'] || !hash_equals($captchaData['code'], strtolower($request->captcha_code))) {
                // 验证错误就清除缓存
                Cache::forget($request->captcha_key);
                return responseFail('验证码错误');
            }

            // 清除图形验证码
            Cache::forget($request->captcha_key);

            $credentials = $request->only('email', 'password');
            $credentials['status'] = 'activated';

            if (config('app.use_admin_lte')) {
                if (!$token = auth('admin_web')->attempt($credentials)) {
                    return responseFail('登录失败，密码错误或账号未激活', 401);
                }
            }

            if ((!$token = auth('admin')->attempt($credentials))) {
                return responseFail('登录失败，密码错误或账号未激活', 401);
            }

            return responseSuccess('登录成功',[
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth('admin')->factory()->getTTL() * 60
            ]);
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    public function profile()
    {
        $user = auth('admin')->user();
        return responseSuccess('当前管理员信息',new UserResource($user));
    }

    public function logout()
    {
        if (auth('admin')->check()) {
            auth('admin')->invalidate();
        }
        if (auth('admin_web')->check()) {
            auth('admin_web')->logout();
        }

        return responseSuccess('退出登录成功');
    }

    public function changeProfile(UserProfileRequest $request)
    {
        try {
            $oldPassword = $request->old_password;
            $password = $request->password;
            $avatar = $request->avatar;

            $user = auth('admin')->user();

            if ($oldPassword && $password && !hash_equals($oldPassword, $password))
            {
                if(!Hash::check($oldPassword, $user->password)){
                    return responseFail('原密码错误');
                }
                $user->password = bcrypt($password);
            }

            if ($avatar)
            {
                $user->avatar = $avatar;
            }

            $user->save();

            return responseSuccess('修改密码成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit');
        if ($limit)
        {
            $paginationData = User::orderBy('id','desc')->paginate($limit);
            $items = UserResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = User::all();
            $items = UserResource::collection($paginationData);
            $total = $paginationData->count();
        }

        return responseSuccess('管理员列表',[
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserStoreRequest  $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'avatar' => $request->avatar,
                'email' => $request->email,
                'super_admin' => boolval($request->super_admin),
                'introduction' => $request->introduction,
                'status' => $request->status,
                'password' => bcrypt($request->password),
            ]);

            $user->setRoles($request->roles_ids);
            $user->save();

            return responseSuccess('管理员创建成功', new UserResource($user));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return responseSuccess('管理员信息',new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest  $request
     * @param  User  $user
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->super_admin = boolval($request->super_admin);
            $user->introduction = $request->introduction;
            $user->avatar = $request->avatar;
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->setRoles($request->roles_ids);
            $user->save();

            return responseSuccess('管理员更新成功', new UserResource($user));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return responseSuccess('管理员删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * 管理员日志
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logs(Request $request)
    {
        $user_id = $request->get('user_id');
        $limit = $request->get('limit');
        $paginationData = UserLog::when($user_id, function ($query, $user_id) {
            return $query->where('user_id',$user_id);
        })->orderBy('id','desc')->paginate($limit);
        $items = UserLogResource::collection($paginationData);
        $paginationDataArray = $paginationData->toArray();
        $total = $paginationDataArray['total'];

        return responseSuccess('日志列表',[
            'items' => $items,
            'total' => $total
        ]);
    }
}
