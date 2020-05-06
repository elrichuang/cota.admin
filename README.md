## 项目概述

COTA-ADMIN 基于Laravel 6开发，用于一般商城、网站开发用的后台及前端接口框架。

## 更新说明

### 版本4.0.1.20200505

1. 增加默认管理后台，前端使用`adminlte 3`开发；
2. 增加导入初始化数据命令`cota:data-init`；

## 代码开发规范

https://learnku.com/docs/laravel-specification


## 资源

### 付费教程

https://learnku.com

账号：13640800070
密码：cota1310


### 国内 composer 镜像：

阿里云：https://developer.aliyun.com/mirror

### 其他常用镜像资源：

清华：https://mirrors.tuna.tsinghua.edu.cn/

brew 镜像设置：https://mirror.tuna.tsinghua.edu.cn/help/homebrew/


## 开发注意点

1. 不使用Laravel页面模板或Laravel UI，只写供前端调用的接口；
2. 除了默认后台的页面，接口不使用Session，统一使用JWT；
3. 本地开发使用file缓存，测试环境或者线上使用redis缓存；
4. 日志使用`Log` `Facades`保存在log文件中，暂未接入阿里云日志服务；
5. 前端使用基于`vue-element-admin`
6. 所有 Model 继承 BaseModel；
7. 后台接口继承`Api\Admin\Controller`；
8. 前端接口继承`Api\Controller`;
9. 表单验证规则统一写在`Request`类，统一继承`BaseRequest`类；
10. 权限判断统一使用`Policy`；
11. 独立第三方库或自定义类放在`App\Libaries`；
12. 优先使用`composer`安装库；
13. 辅助函数写在`app\helpers.php`；
14. 图片处理类，微信服务器信息处理类放在`app\Handlers`；
15. 后台接口路由设置`routes\admin_api.php`，前端接口路由设置`routes\api.php`；
16. 数据库设计一律使用Laravel的migrate，不再使用`MySQL Workbench`；
17. 所有 `.env`文件里的常量不能直接通过`env('XXX')`在代码中直接使用，必须写相应的 config 文件保存在`app\config`目录，然后在代码中通过`config('xxx.xx.xx')`调用；
18. Laravel 6.0后不需要使用`DingoApi`框架处理 api 接口，直接用原生的 routes；
19. 测试接口时资源控制器处理PUT或者PATCH请求时，需要将"Content-Type"设为"application/x-www-form-urlencode"才能接收到，DELETE不用
20. 部分更新请求用PATCH
21. 密码确认字段为`password_confirmation`，其他确认字段名`xxx_confirmation`；
22. 所有金额保存到数据库使用 Demical 字段，长度（10，2）；
23. 数据库的任何修改都要新建新的migrate，不能修改已有的migrate文件，不然发布到线上后是不会执行的；
24. 创建新表时，除了 migrate，还要有对应的 factory 和 seeder，用于测试时创建测试数据；
25. 所有字段都必须要有注释！！！
26. 数据库统一使用`utf8mb4`和`utf8mb4-unicode-ci`;
27. 如果在本地开发时在`.env`文件里新增加了设置，需要同步到`.env.example`文件;


## 功能如下

### 默认管理后台

- 前端使用`adminlte 3`；
- 模板位于目录`resources`-`views`；
- 前端静态文件`js`，`css`等位于目录`public`-`static`；
- 后台菜单权限功能已实现，支持多角色；
- 有`单图片上传组件`和`百度编辑器`，图片上传均直传到OSS，编辑器支持批量上传`图片`，`涂鸦`，`视频`，`附件`；
- 网页端登录状态使用 `session`，接口使用`jwt`，登录后会在浏览器记录`jwt`的 `cookie`，过期时间与`session`一样，默认 120 分钟，当`cookie`过期的同时`session`会强行过期；

### 后台接口

- 管理员增删改查，修改状态；
- 角色管理
- 能力管理
- 会员管理
- 管理员日志
- 文章管理
- 文章分类管理
- 页面管理
- 商家管理
- 店铺管理
- 幻灯片管理
- 幻灯片元素管理
- 品牌管理
- 商品分类管理
- 商品SPU管理
- 商品SKU管理
- 购物车管理
- 订单管理
- 快递公司管理
- 会员送货地址管理
- 退款单管理


### 前端接口

- 会员注册登录（手机验证码，手机密码，昵称密码）
- 会员个人信息，会员退出登录
- 手机号绑定
- 手机验证码，图形验证码
- 图片通过JS直传OSS
- 微信H5，小程序授权登录
- 微信JSSDK签名接口
- 微信支付
- 通莞支付
- 购物车接口
- 页面接口
- 文章接口
- 收件地址接口
- 商品分类接口
- 商品接口
- 订单接口


## 计划中功能

### 后台接口

~~1. 权限控制Policy 丢弃~~
1. 商品导入导出
1. 订单导出
1. 快递信息导入
1. 会员信息导入，导出
4. 会员统计
5. 商品统计
6. 订单统计

### 前端接口

~~1. 权限控制Policy 丢弃~~
1. 团购
1. 优惠券：第二件半价，代金券
1. 秒杀，砍价
1. 预购
1. 兑换卡
1. 充值
1. 二级分佣，提现
2. 申请退款接口，按order_sku退款；
2. 支付宝支持
3. 阿里云日志
4. 微信图片，视频，语音上传（第三方框架有，未写接口）
5. 微信开放平台（第三方框架有，未写接口）
6. H5抽奖，兑奖接口；



## 运行环境

- PHP >= 7.2.0
- BCMath PHP 拓展
- Ctype PHP 拓展
- JSON PHP 拓展
- Mbstring PHP 拓展
- OpenSSL PHP 拓展
- PDO PHP 拓展
- Tokenizer PHP 拓展
- XML PHP 拓展
- 数据库使用MySQL 5.7


## 开发环境部署 / 安装

### 开发环境
1. 下载代码到本地；
3. 运行`composer install`；
4. 复制`.env.example`为`.env`，修改`.env`中的相关设置；
5. 目录storage和bootstrap/cache需要可写权限；
运行命令`php artisan key:generate`和`php artisan jwt:secret`生成新的密匙；
6. 如果使用的是MySQL 5.6，需要修改文件`AppServiceProvider`的`27`行，否则数据库索引报错；
7. 运行`php artisan migrate`，建立数据表结构。如果需要测试数据，就运行`php artisan migrate --seed`；
8. 运行`php artisan cota:data-init`，导入初始化数据；
9. 运行`php artisan serve`，打开本地网站；


### 代码上线

- Nginx 配置参考代码：

```nginx
server {
    listen 80;
    server_name www.domain.com #域名

    # SSL设置
    # listen  443 ssl;
    # ssl_certificate   /.../fullchain.pem;
    # ssl_certificate_key  /.../privkey.pem;
    # ssl_session_timeout 5m;
    # ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
    # ssl_ciphers AESGCM:ALL:!DH:!EXPORT:!RC4:+HIGH:!MEDIUM:!LOW:!aNULL:!eNULL;
    # ssl_prefer_server_ciphers on;
    
    # error_page 497 https://$host&uri?$args;

    root /.../php/public; #根目录

    index index.html index.htm index.php;

    charset utf-8;

    # 跨域
    add_header 'Access-Control-Allow-Origin' '*';
    add_header 'Access-Control-Allow-Credentials' 'true';
    add_header 'Access-Control-Allow-Methods' 'DELETE, OPTION, POST, GET';
    add_header 'Access-Control-Allow-Headers' 'X-Requested-With, Content-Type, Authorization';

    add_header Strict-Transport-Security "max-age=63072000; includeSubdomains; preload";
    add_header X-Content-Type-Options nosniff;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9073;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

- 目录`storage`和`bootstrap/cache`需要可写权限；
- 运行命令`php artisan key:generate`和`php artisan jwt:secret`生成新的密匙；
- 运行命令`php artisan config:cache`和`php artisan route:cache`优化；
- 每次更新都需要执行`php artisan config:clear`，`php artisan config:cache`，`php artisan route:clear`，`php artisan route:cache`；
- 运行命令`php artisan clear-compiled`，`php artisan optimize --force`优化类加载；
- 优化composer`composer dumpautoload -o`,如果运行了`php artisan optimize --force`就不需要了；
- 程序使用`redis`缓存；
- 关闭`debug`，`APP_DEBUG=false`；


## 服务器架构说明

![image](https://cotaxingcloud.oss-cn-hangzhou.aliyuncs.com/images/server.png)

## 扩展包说明


扩展包 | 一句话描述 | 本项目应用场景
---|---|---
caouecs/laravel-lang | 验证规则语言包 | 验证规则错误中文回复
fruitcake/laravel-cors | Laravel CORS接口处理 | 为接口增加跨域支持
gregwar/captcha | 图形验证码 | 图形验证码
intervention/image | 图片处理 | 图片上传后压缩裁减加水印
overtrue/easy-sms | 发送手机验证码 | 登录，注册，绑定手机时验证手机号
overtrue/wechat | EasyWechat微信处理框架 | 微信登录，小程序登录，公众号，小程序，支付，开放平台等微信相关
tymon/jwt-auth | JWT验证 | 接口授权
guzzlehttp/guzzle | HTTP 请求套件 | 请求通莞金服接口

## 自定义 Artisan 命令列表

- 导入初始化数据`php artisan cota:data-init`

## 队列列表
