<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\ArticleCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');

        //$this->authorizeResource(User::class,'user');
    }

    /**
     * 列表
     * @return Application|Factory|View
     */
    public function index() {
        $items = ArticleCategory::getTreeData();
        return view('admin.articles.categories.index',[
            'items' => $items
        ]);
    }

    /**
     * 添加
     * @return Application|Factory|View
     */
    public function store() {
        $allCategories = ArticleCategory::getTreeData();
        return view('admin.articles.categories.store',[
            'allCategories' => $allCategories
        ]);
    }

    /**
     * 编辑
     * @param Request $request
     * @return Application|Factory|View
     */
    public function profile(Request $request) {
        if (!$request->id) {
            abort(404);
        }

        $article_category = ArticleCategory::where(['id'=>$request->id])->first();
        if (!$article_category) {
            abort(404);
        }

        $allCategories = ArticleCategory::getTreeData();
        return view('admin.articles.categories.store', [
            'item' => $article_category,
            'allCategories' => $allCategories
        ]);
    }
}
