<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 图片验证码
Route::any('captchas', 'CaptchasController@store')->name('captchas.store');
// 短信验证码
Route::post('verification_codes', 'VerificationCodesController@store')->name('verificationCodes.store');

// 登录
Route::post('members/login', 'MembersController@login')->name('members.login');
// 刷新token
Route::post('members/refresh_token', 'MembersController@refreshToken')->name('members.refreshToken');
// 删除token
Route::post('members/logout', 'MembersController@logout')->name('members.logout');
Route::post('members/register', 'MembersController@store')->name('members.register');
Route::get('members/profile', 'MembersController@show')->name('members.profile');
Route::patch('members/profile', 'MembersController@update')->name('members.update');
Route::patch('members/bind', 'MembersController@bind')->name('members.bind');

// 上传图片到本地服务器
Route::post('images', 'ImagesController@store')->name('images.store');

// 微信公众号
Route::get('wechat/officials/serve','Wechat\OfficialsController@serve')->name('wechat.officials.serve');
Route::get('wechat/officials/oauth','Wechat\OfficialsController@oauth')->name('wechat.officials.oauth');
Route::get('wechat/officials/oauth_callback','Wechat\OfficialsController@oauthCallback')->name('wechat.officials.oauthCallback');
Route::get('wechat/officials/jssdk','Wechat\OfficialsController@jssdk')->name('wechat.officials.jssdk');

// 微信小程序
Route::get('wechat/miniapps/oauth','Wechat\MiniappsController@oauth')->name('wechat.miniapps.oauth');
// 微信支付回调
Route::get('wechat/payments/notify','Wechat\PaymentsController@notify')->name('wechat.payments.notify');

// 页面
Route::apiResource('pages', 'PagesController')->only(['index','show']);
// 文章
Route::apiResource('articles', 'ArticlesController')->only(['index','show']);
Route::apiResource('article_categories', 'ArticleCategoriesController')->only(['index']);
// 商品
Route::apiResource('spus', 'SpusController')->only(['index']);
Route::apiResource('spu_categories', 'SpuCategoriesController')->only(['index']);
Route::apiResource('skus', 'SkusController')->only(['show']);
// 会员地址
Route::apiResource('member_addresses', 'MemberAddressesController');
Route::apiResource('carts', 'CartsController');
// 会员订单
Route::post('orders/check_cart', 'OrdersController@checkCart')->name('orders.checkCart');
Route::post('orders/confirm', 'OrdersController@confirm')->name('orders.confirm');
Route::post('orders/pay', 'OrdersController@pay')->name('orders.pay');
Route::post('orders/cancel', 'OrdersController@cancel')->name('orders.cancel');
Route::post('orders/receive', 'OrdersController@receive')->name('orders.receive');
Route::get('orders', 'OrdersController@all')->name('orders.all');
Route::get('orders/express', 'OrdersController@express')->name('orders.express');
Route::get('orders/{order}', 'OrdersController@detail')->name('orders.detail');
// 商家订单
Route::get('merchant_orders/get_invoice/{merchant_order}', 'MerchantOrdersController@getInvoice')->name('merchantOrders.getInvoice');

// 通莞金服通知回调
Route::post('tgposps/notify', 'TgpospsController@notify')->name('tgposps.notify');
