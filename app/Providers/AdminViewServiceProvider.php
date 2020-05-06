<?php


namespace App\Providers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminViewServiceProvider extends ServiceProvider
{
    /**
     * 注册任何应用服务
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * 引导任何应用程序服务
     *
     * @return void
     */
    public function boot()
    {
        // 使用基于合成器的类...
        View::composer(
            '*', 'App\Http\View\Composers\AbilityComposer'
        );
    }
}
