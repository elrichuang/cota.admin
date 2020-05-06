<?php


namespace App\Http\View\Composers;


use App\Models\Ability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AbilityComposer
{
    /**
     * 将数据绑定到视图
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if(auth('admin_web')->check()) {
            $abilitiesIds = [];
            $user = auth('admin_web')->user();
            if ($user->super_admin) {
                // 超级管理员
                $view->with('abilities', Ability::getTreeData());
            }else {
                foreach ($user->roles as $role) {
                    $abilitiesIds = array_merge($abilitiesIds,$role->abilities()->allRelatedIds()->toArray());
                }
                $view->with('abilities', Ability::getTreeData($abilitiesIds, true));
            }
        }

        $ability = Ability::where(['alias'=>Route::currentRouteName()])->first();
        $view->with('ability', $ability);
    }
}
