<?php

namespace App\Models;


class ArticleCategory extends BaseModel
{
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','parent_id','title','num_sort'
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

    public function articles()
    {
        return $this->hasMany('App\Models\Article');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\ArticleCategory','id','parent_id');
    }

    /**
     * 获取树形菜单数据
     * @param array $categoriesIds
     * @return array
     */
    public static function getTreeData($categoriesIds = [])
    {
        $outputData = [];

        $categories = self::where([
            'parent_id' => 0
        ])->when($categoriesIds,function($query,$categoriesIds){
            return $query->whereIn('id',$categoriesIds);
        })->orderBy('num_sort','asc')->get();


        foreach ($categories as $category) {
            $childrenData = self::getTreeChildData($category, $categoriesIds);
            if (count($childrenData) > 0) {
                $category->children = $childrenData;
            }

            array_push($outputData,$category);
        }

        return $outputData;
    }

    protected static function getTreeChildData(ArticleCategory $category, $categoriesIds = [])
    {
        $outputData = [];
        $children = self::where([
            'parent_id' => $category->id,
        ])->when($categoriesIds,function($query,$categoriesIds){
            return $query->whereIn('id',$categoriesIds);
        })->orderBy('num_sort','asc')->get();

        foreach ($children as $child) {
            $grandChildrenArray = self::getTreeChildData($child, $categoriesIds);
            if (count($grandChildrenArray) > 0) {
                $child->children = $grandChildrenArray;
            }
            array_push($outputData, $child);
        }

        return $outputData;
    }
}
