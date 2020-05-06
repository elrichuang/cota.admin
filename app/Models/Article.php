<?php

namespace App\Models;

class Article extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'sub_title',
        'author',
        'summary',
        'content',
        'thumb',
        'num_like',
        'num_view',
        'num_sort',
        'on_top_at',
        'recommend_at',
        'published_at'
    ];

    public function category()
    {
        return $this->hasOne('App\Models\ArticleCategory','id','category_id');
    }
}
