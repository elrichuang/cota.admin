<?php

namespace App\Http\Middleware;

use App\Models\UserLog;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AdminUserLogs
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // 运行动作
        if($request->route()->getName() !== 'admin.users.logs')
        {
            if ($request->expectsJson()) {
                $user = $request->user('admin');
            }else {
                $user = $request->user('admin_web');
            }

            if ($user)
            {
                $userLog = new UserLog();
                $userLog->user_id = $user->id;
                $userLog->uri = Route::currentRouteName();
                $userLog->request_method = $request->method();
                if ($request->route()->getName() !== 'admin.images.store') {
                    $userLog->request_data = json_encode($request->all());
                }else {
                    $userLog->request_data = '图片文件';
                }
                if ($request->expectsJson()) {
                    $userLog->response_data = $response->getContent();
                }else {
                    if ($response->getOriginalContent() instanceof View) {
                        $userLog->response_data = $response->getOriginalContent()->getName();
                    }else {
                        if($response->exception) {
                            $userLog->response_data = $response->exception->getMessage();
                        }else {
                            $userLog->response_data = $response->getContent();
                        }
                    }
                }
                $userLog->ip_address = $request->ip();
                $userLog->user_agent = $request->userAgent();
                $userLog->year = Carbon::now()->year;
                $userLog->month = Carbon::now()->month;
                $userLog->day = Carbon::now()->day;
                $userLog->hour = Carbon::now()->hour;
                $userLog->minute = Carbon::now()->minute;
                $userLog->save();
            }
        }

        return $response;
    }
}
