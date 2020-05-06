<?php

namespace App\Http\Middleware;

use App\Models\Ability;
use Closure;
use Illuminate\Support\Facades\Route;

/**
 * Class CheckAdminApiTokenExist
 * @package App\Http\Middleware
 * 检查前端api的cookie是否已过期
 */
class CheckAdminApiTokenExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Route::currentRouteName() != 'admin.users.login') {
            // 除去登录页面都要检查
            if (!$request->cookie(config('admin.api_cookie_name'))) {
                // 前端cookie不存在
                auth('admin_web')->logout();
                return response()->redirectToRoute('admin.users.login');
            }

            // 检查权限
            $this->_authorize();
        }

        return $next($request);
    }

    /**
     * 检查权限
     */
    protected function _authorize()
    {
        if ($user = auth('admin_web')->user()) {
            // 已登录
            if (!$user->super_admin) {
                // 不是超级管理员，获取所有权限的 ID
                $abilitiesIds = [];
                foreach ($user->roles as $role) {
                    $abilitiesIds = array_merge($abilitiesIds,$role->abilities()->allRelatedIds()->toArray());
                }

                // 通过当前路由获取权限
                $currentAbility = Ability::where([
                    'alias' => Route::currentRouteName(),
                    'type' => Ability::TYPE_VIEW,
                    'status' => Ability::STATUS_ACTIVATED
                ])->first();
                if (!$currentAbility) {
                    // 未加入权限数据库的默认没有权限
                    abort(403);
                }

                if (!in_array($currentAbility->id,$abilitiesIds)) {
                    // 没有该页面的权限
                    abort(403);
                }
            }
        }else {
            // 未登录
            abort(403);
        }
    }
}
