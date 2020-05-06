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

// 管理员
Route::post('users/login', 'UsersController@login')->name('users.login');
Route::post('users/logout', 'UsersController@logout')->name('users.logout');
Route::post('users/delete_many', 'UsersController@destroyMany')->name('users.destroyMany');
Route::get('users/profile', 'UsersController@profile')->name('users.profile');
Route::get('users/logs', 'UsersController@logs')->name('users.logs');
Route::patch('users/profile', 'UsersController@changeProfile')->name('users.changeProfile');
Route::apiResource('users', 'UsersController');
// 权限
Route::get('abilities/tree', 'AbilitiesController@getTreeData')->name('abilities.getTreeData');
Route::post('abilities/delete_many', 'AbilitiesController@destroyMany')->name('abilities.destroyMany');
Route::apiResource('abilities', 'AbilitiesController');
Route::apiResource('roles', 'RolesController');
Route::post('roles/delete_many', 'RolesController@destroyMany')->name('roles.destroyMany');
Route::apiResource('articles', 'ArticlesController');
Route::post('articles/delete_many', 'ArticlesController@destroyMany')->name('articles.destroyMany');
Route::apiResource('pages', 'PagesController');
Route::post('pages/delete_many', 'PagesController@destroyMany')->name('pages.destroyMany');
Route::apiResource('article_categories', 'ArticleCategoriesController');
Route::post('article_categories/delete_many', 'ArticleCategoriesController@destroyMany')->name('article_categories.destroyMany');
Route::apiResource('merchants', 'MerchantsController');
Route::apiResource('stores', 'StoresController');
Route::apiResource('slices', 'SlicesController');
Route::post('slices/delete_many', 'SlicesController@destroyMany')->name('slices.destroyMany');
Route::apiResource('slice_items', 'SliceItemsController');
Route::post('slice_items/delete_many', 'SliceItemsController@destroyMany')->name('slice_items.destroyMany');
Route::apiResource('brands', 'BrandsController');
Route::apiResource('spu_categories', 'SpuCategoriesController');
Route::apiResource('spus', 'SpusController');
Route::apiResource('skus', 'SkusController');
Route::apiResource('carts', 'CartsController')->only(['index']);
Route::apiResource('orders', 'OrdersController')->only(['index','show']);
Route::apiResource('refund_orders', 'RefundOrdersController')->only(['index','show']);
Route::apiResource('merchant_orders', 'MerchantOrdersController')->only(['index','show']);
Route::apiResource('order_skus', 'OrderSkusController')->only(['index','show']);
Route::apiResource('order_statuses', 'OrderStatusesController')->only(['index','show']);
Route::apiResource('order_sku_statuses', 'OrderSkuStatusesController')->only(['index','show']);
Route::apiResource('expresses', 'ExpressesController');
Route::apiResource('member_addresses', 'MemberAddressesController')->only(['index','show']);
Route::apiResource('refund_orders', 'RefundOrdersController')->only(['index','show']);
Route::post('refund_orders/reject/{refund_order}', 'RefundOrdersController@reject')->name('refundOrders.reject');
Route::post('refund_orders/accept/{refund_order}', 'RefundOrdersController@accept')->name('refundOrders.accept');
Route::apiResource('members', 'MembersController');
Route::post('members/delete_many', 'MembersController@destroyMany')->name('members.destroyMany');

// 上传图片
Route::post('images', 'ImagesController@store')->name('images.store');
Route::get('images/policy', 'ImagesController@ossPolicy')->name('images.ossPolicy');
Route::get('images/oss_callback', 'ImagesController@ossCallback')->name('images.ossCallback');
