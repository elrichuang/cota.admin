<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 仪表盘
Route::get('', 'DashboardController@index')->name('dashboard');
Route::get('users', 'UsersController@index')->name('users.all');
Route::get('users/log', 'UsersController@log')->name('users.log');
Route::get('users/login', 'UsersController@login')->name('users.login');
Route::get('users/store', 'UsersController@store')->name('users.store');
Route::get('users/profile/{user}', 'UsersController@profile')->name('users.profile');

// 权限
Route::get('abilities', 'AbilitiesController@index')->name('abilities.all');
Route::get('abilities/store', 'AbilitiesController@store')->name('abilities.store');
Route::get('abilities/profile', 'AbilitiesController@profile')->name('abilities.profile');

// 角色
Route::get('roles', 'RolesController@index')->name('roles.all');
Route::get('roles/store', 'RolesController@store')->name('roles.store');
Route::get('roles/profile/{role}', 'RolesController@profile')->name('roles.profile');

// 会员
Route::get('members', 'MembersController@index')->name('members.all');
Route::get('members/store', 'MembersController@store')->name('members.store');
Route::get('members/profile/{member}', 'MembersController@profile')->name('members.profile');

// 页面
Route::get('pages', 'PagesController@index')->name('pages.all');
Route::get('pages/store', 'PagesController@store')->name('pages.store');
Route::get('pages/profile/{page}', 'PagesController@profile')->name('pages.profile');

// 文章分类
Route::get('article_categories', 'ArticleCategoriesController@index')->name('article_categories.all');
Route::get('article_categories/store', 'ArticleCategoriesController@store')->name('article_categories.store');
Route::get('article_categories/profile', 'ArticleCategoriesController@profile')->name('article_categories.profile');

// 文章
Route::get('articles', 'ArticlesController@index')->name('articles.all');
Route::get('articles/store', 'ArticlesController@store')->name('articles.store');
Route::get('articles/profile/{article}', 'ArticlesController@profile')->name('articles.profile');

// 幻灯片
Route::get('slices', 'SlicesController@index')->name('slices.all');
Route::get('slices/store', 'SlicesController@store')->name('slices.store');
Route::get('slices/profile/{slice}', 'SlicesController@profile')->name('slices.profile');

// 幻灯片图片
Route::get('slice_items', 'SliceItemsController@index')->name('slice_items.all');
Route::get('slice_items/store', 'SliceItemsController@store')->name('slice_items.store');
Route::get('slice_items/profile/{sliceItem}', 'SliceItemsController@profile')->name('slice_items.profile');
