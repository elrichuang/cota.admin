<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

/**
 * 返回成功响应
 * @param string $message 信息
 * @param array $data 数据
 * @param int $code 返回码
 * @param int $statusCode 状态码
 * @return JsonResponse
 */
function responseSuccess($message, $data = [], $code = 0, $statusCode = 200) {
    return responseJson(true, $message, $data, $code, $statusCode);
}

/**
 * 返回失败响应
 * @param string $message 信息
 * @param array $data 数据
 * @param int $code 返回码
 * @param int $statusCode 状态码
 * @return JsonResponse
 */
function responseFail($message, $code = 500, $statusCode = 200, $data = []) {
    return responseJson(false, $message, $data, $code, $statusCode);
}

/**
 * 返回Json响应
 * @param bool $success
 * @param string $message
 * @param array $data
 * @param int $code
 * @param int $statusCode 状态码
 * @return JsonResponse
 */
function responseJson($success, $message, $data = [], $code = 0, $statusCode = 200) {
    return response()->json([
        'success' => $success,
        'code' =>$code,
        'message'=>$message,
        'data'=>$data
    ])->setStatusCode($statusCode);
}

/**
 * 检查是否选中当前菜单
 * @param \App\Models\Ability $ability
 * @return string
 */
function checkMenuActive($ability) {
    if (!$ability->use_url) {
        if ($ability->alias == Route::currentRouteName()) {
            return 'active';
        }
    }else {
        if ($ability->url == url()->current()) {
            return 'active';
        }
    }

    return '';
}

/**
 * 检查是否选中子菜单
 * @param array $childrenAbilities
 * @return string
 */
function checkMenuOpen($childrenAbilities) {
    foreach ($childrenAbilities as $childrenAbility) {
        if (count($childrenAbility->children) > 0) {
            foreach ($childrenAbility->children as $child) {
                if (checkMenuActive($child)) {
                    return 'menu-open';
                }
            }
        }else {
            if (checkMenuActive($childrenAbility)) {
                return 'menu-open';
            }
        }
    }
    return '';
}

function stringIsInt($str)
{
    return 0 === strcmp($str,(int)$str);
}
