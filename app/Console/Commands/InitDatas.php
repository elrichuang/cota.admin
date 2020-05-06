<?php

namespace App\Console\Commands;

use App\Models\Ability;
use App\Models\User;
use Illuminate\Console\Command;

class InitDatas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cota:data-init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化后台数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            if ($this->confirm('是否导入初始化数据？注意！请勿重复导入。')) {
                // 超级管理员
                $user = User::create(
                    [
                        'name' => 'admin',
                        'avatar' => config('app.default_avatar'),
                        'email' => 'admin@cootaa.com',
                        'password' => bcrypt('cota1310'),
                        'super_admin'=> true,
                        'introduction'=> '超级管理员，拥有最高权限',
                        'status'=>'activated',
                    ]
                );

                // 权限
                $abilities = [
                    [
                        'parent_id' => 0,
                        'name' => '仪表盘',
                        'icon' => 'fa-tachometer-alt',
                        'alias' => 'admin.dashboard',
                        'remark' => '仪表盘',
                        'show_on_menu' => true,
                        'num_sort' => 10
                    ],
                    [
                        'parent_id' => 0,
                        'name' => '页面管理',
                        'icon' => 'fa-file',
                        'alias' => '',
                        'remark' => '页面管理',
                        'show_on_menu' => true,
                        'num_sort' => 20
                    ],
                    [
                        'parent_id' => 0,
                        'name' => '文章管理',
                        'icon' => 'fa-atlas',
                        'alias' => '',
                        'remark' => '文章管理',
                        'show_on_menu' => true,
                        'num_sort' => 30
                    ],
                    [
                        'parent_id' => 0,
                        'name' => '幻灯片管理',
                        'icon' => 'fa-images',
                        'alias' => '',
                        'remark' => '幻灯片管理',
                        'show_on_menu' => true,
                        'num_sort' => 40
                    ],
                    [
                        'parent_id' => 0,
                        'name' => '会员管理',
                        'icon' => 'fa-user-tie',
                        'alias' => '',
                        'remark' => '会员管理',
                        'show_on_menu' => true,
                        'num_sort' => 50
                    ],
                    [
                        'parent_id' => 0,
                        'name' => '权限管理',
                        'icon' => 'fa-broadcast-tower',
                        'alias' => '',
                        'remark' => '权限管理',
                        'show_on_menu' => true,
                        'num_sort' => 60
                    ],
                    [
                        'parent_id' => 0,
                        'name' => '角色管理',
                        'icon' => 'fa-user-tag',
                        'alias' => '',
                        'remark' => '角色管理',
                        'show_on_menu' => true,
                        'num_sort' => 70
                    ],
                    [
                        'parent_id' => 0,
                        'name' => '管理员管理',
                        'icon' => 'fa-users',
                        'alias' => '',
                        'remark' => '管理员管理',
                        'show_on_menu' => true,
                        'num_sort' => 80
                    ],
                    [
                        'parent_id' => 2,
                        'name' => '添加页面',
                        'icon' => 'fa-plus',
                        'alias' => 'admin.pages.store',
                        'remark' => '添加页面',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 2,
                        'name' => '页面列表',
                        'icon' => 'fa-list',
                        'alias' => 'admin.pages.all',
                        'remark' => '页面列表',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 2,
                        'name' => '编辑页面',
                        'icon' => 'fa-edit',
                        'alias' => 'admin.pages.profile',
                        'remark' => '编辑页面',
                        'show_on_menu' => false,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 3,
                        'name' => '添加文章分类',
                        'icon' => 'fa-plus',
                        'alias' => 'admin.article_categories.store',
                        'remark' => '添加文章分类',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 3,
                        'name' => '文章分类列表',
                        'icon' => 'fa-list',
                        'alias' => 'admin.article_categories.all',
                        'remark' => '文章分类列表',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 3,
                        'name' => '编辑文章分类',
                        'icon' => 'fa-edit',
                        'alias' => 'admin.article_categories.profile',
                        'remark' => '编辑文章分类',
                        'show_on_menu' => false,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 3,
                        'name' => '添加文章',
                        'icon' => 'fa-plus',
                        'alias' => 'admin.articles.store',
                        'remark' => '添加文章',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 3,
                        'name' => '文章列表',
                        'icon' => 'fa-list',
                        'alias' => 'admin.articles.all',
                        'remark' => '文章列表',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 3,
                        'name' => '编辑文章',
                        'icon' => 'fa-edit',
                        'alias' => 'admin.articles.profile',
                        'remark' => '编辑文章',
                        'show_on_menu' => false,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 4,
                        'name' => '添加幻灯片',
                        'icon' => 'fa-plus',
                        'alias' => 'admin.slices.store',
                        'remark' => '添加幻灯片',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 4,
                        'name' => '幻灯片列表',
                        'icon' => 'fa-list',
                        'alias' => 'admin.slices.all',
                        'remark' => '幻灯片列表',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 4,
                        'name' => '编辑幻灯片',
                        'icon' => 'fa-edit',
                        'alias' => 'admin.slices.profile',
                        'remark' => '编辑幻灯片',
                        'show_on_menu' => false,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 4,
                        'name' => '添加幻灯片图片',
                        'icon' => 'fa-plus',
                        'alias' => 'admin.slice_items.store',
                        'remark' => '添加幻灯片图片',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 4,
                        'name' => '幻灯片图片列表',
                        'icon' => 'fa-list',
                        'alias' => 'admin.slice_items.all',
                        'remark' => '幻灯片图片列表',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 4,
                        'name' => '编辑幻灯片图片',
                        'icon' => 'fa-edit',
                        'alias' => 'admin.slice_items.profile',
                        'remark' => '编辑幻灯片图片',
                        'show_on_menu' => false,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 5,
                        'name' => '添加会员',
                        'icon' => 'fa-plus',
                        'alias' => 'admin.members.store',
                        'remark' => '添加会员',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 5,
                        'name' => '会员列表',
                        'icon' => 'fa-list',
                        'alias' => 'admin.members.all',
                        'remark' => '会员列表',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 5,
                        'name' => '编辑会员',
                        'icon' => 'fa-edit',
                        'alias' => 'admin.members.profile',
                        'remark' => '编辑会员',
                        'show_on_menu' => false,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 6,
                        'name' => '添加权限',
                        'icon' => 'fa-plus',
                        'alias' => 'admin.abilities.store',
                        'remark' => '添加幻灯片',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 6,
                        'name' => '权限列表',
                        'icon' => 'fa-list',
                        'alias' => 'admin.abilities.all',
                        'remark' => '权限列表',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 6,
                        'name' => '编辑权限',
                        'icon' => 'fa-edit',
                        'alias' => 'admin.abilities.profile',
                        'remark' => '编辑权限',
                        'show_on_menu' => false,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 7,
                        'name' => '添加角色',
                        'icon' => 'fa-plus',
                        'alias' => 'admin.roles.store',
                        'remark' => '添加角色',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 7,
                        'name' => '角色列表',
                        'icon' => 'fa-list',
                        'alias' => 'admin.roles.all',
                        'remark' => '角色列表',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 7,
                        'name' => '编辑角色',
                        'icon' => 'fa-edit',
                        'alias' => 'admin.roles.profile',
                        'remark' => '编辑角色',
                        'show_on_menu' => false,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 8,
                        'name' => '添加管理员',
                        'icon' => 'fa-user-plus',
                        'alias' => 'admin.users.store',
                        'remark' => '添加管理员',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 8,
                        'name' => '管理员列表',
                        'icon' => 'fa-list',
                        'alias' => 'admin.users.all',
                        'remark' => '管理员列表',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 8,
                        'name' => '管理员日志',
                        'icon' => 'fa-list-alt',
                        'alias' => 'admin.users.log',
                        'remark' => '管理员日志',
                        'show_on_menu' => true,
                        'num_sort' => 500
                    ],
                    [
                        'parent_id' => 8,
                        'name' => '编辑管理员',
                        'icon' => 'fa-edit',
                        'alias' => 'admin.users.profile',
                        'remark' => '编辑管理员',
                        'show_on_menu' => false,
                        'num_sort' => 500
                    ],
                ];

                // 循环创建数据
                foreach ($abilities as $ability) {
                    Ability::create(
                        [
                            'user_id' => $user->id,
                            'parent_id' => $ability['parent_id'],
                            'name' => $ability['name'],
                            'icon' => $ability['icon'],
                            'use_url' => false,
                            'alias' => $ability['alias'],
                            'remark' => $ability['remark'],
                            'status' => Ability::STATUS_ACTIVATED,
                            'type' => Ability::TYPE_VIEW,
                            'show_on_menu' => $ability['show_on_menu'],
                            'num_sort' => $ability['num_sort']
                        ]
                    );
                }

                $this->info('导入初始化数据成功！');
            }else {
                $this->info('什么都没做就退出了。');
            }
        }catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
