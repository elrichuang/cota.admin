<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;

class Ability extends BaseModel
{
    use SoftDeletes;

    const STATUS_ACTIVATED = 'activated';
    const STATUS_DEACTIVATED = 'deactivated';

    const TYPE_VIEW = 'view';
    const TYPE_API = 'api';

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','parent_id','name','alias','remark', 'status', 'type', 'icon', 'num_sort','show_on_menu','use_url','url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'children'
    ];

    /**
     * @return array
     */
    public $children = [];

    public function roles()
    {
        return $this->belongsToMany(Role::Class, 'role_abilities','ability_id','role_id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Ability','id','parent_id');
    }

    /**
     * 获取树形菜单数据
     * @param array $abilitiesIds
     * @param boolean $isForAuth
     * @return array
     */
    public static function getTreeData($abilitiesIds = [], $isForAuth = false)
    {
        $outputData = [
            'view' => [],
            'api' => []
        ];

        // 视图
        if ($isForAuth) {
            $abilities = self::where([
                'type' => 'view',
                'status' => 'activated',
                'parent_id' => 0
            ])->whereIn('id',$abilitiesIds)->orderBy('num_sort','asc')->get();
        }else {
            $abilities = self::where([
                'type' => 'view',
                'status' => 'activated',
                'parent_id' => 0
            ])->when($abilitiesIds,function($query,$abilitiesIds){
                return $query->whereIn('id',$abilitiesIds);
            })->orderBy('num_sort','asc')->get();
        }

        foreach ($abilities as $ability) {
            $childrenData = self::getTreeChildData($ability, $abilitiesIds,$isForAuth);
            if (count($childrenData) > 0) {
                $ability->children = $childrenData;
            }

            if($ability->alias != '') {
                if(Route::has($ability->alias)) {
                    array_push($outputData['view'],$ability);
                }
            }else {
                array_push($outputData['view'],$ability);
            }
        }

        // 接口，接口没有层级关系
        if ($isForAuth) {
            $abilities = self::where([
                'type' => 'api',
                'status' => 'activated',
            ])->whereIn('id',$abilitiesIds)->orderBy('id','desc')->get();
        }else {
            $abilities = self::where([
                'type' => 'api',
                'status' => 'activated',
            ])->when($abilitiesIds,function($query,$abilitiesIds){
                return $query->whereIn('id',$abilitiesIds);
            })->orderBy('id','desc')->get();
        }

        foreach ($abilities as $ability) {
            if($ability->alias != '') {
                if(Route::has($ability->alias)) {
                    array_push($outputData['api'], $ability);
                }
            }else {
                array_push($outputData['api'], $ability);
            }
        }

        return $outputData;
    }

    protected static function getTreeChildData(Ability $ability, $abilitiesIds = [],$isForAuth = false)
    {
        $outputData = [];
        if ($isForAuth) {
            $children = self::where([
                'parent_id' => $ability->id,
                'type' => $ability->type
            ])->whereIn('id',$abilitiesIds)->orderBy('num_sort','asc')->get();
        }else {
            $children = self::where([
                'parent_id' => $ability->id,
                'type' => $ability->type
            ])->when($abilitiesIds,function($query,$abilitiesIds){
                return $query->whereIn('id',$abilitiesIds);
            })->orderBy('num_sort','asc')->get();
        }

        foreach ($children as $child) {
            $grandChildrenArray = self::getTreeChildData($child, $abilitiesIds,$isForAuth);
            if (count($grandChildrenArray) > 0) {
                $child->children = $grandChildrenArray;
            }
            if($child->alias != '') {
                if(Route::has($child->alias)) {
                    array_push($outputData, $child);
                }
            }else {
                array_push($outputData, $child);
            }
        }

        return $outputData;
    }
}
