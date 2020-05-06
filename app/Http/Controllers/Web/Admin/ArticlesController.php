<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');

        //$this->authorizeResource(User::class,'user');
    }

    /**
     * 列表
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request) {
        $limit = config('admin.page_limit');

        $q = $request->q;
        $category_id = $request->category_id;

        $paginationData = Article::when($q, function($query, $q){
            $query->where('title', 'like', '%'.$q.'%')->orWhere('summary','like', '%'.$q.'%')
                ->orWhere('content','like', '%'.$q.'%')->orWhere('author','like', '%'.$q.'%');
        })->when($category_id, function($query, $category_id){
            $query->where('category_id', $category_id);
        })->orderBy('id','desc')->paginate($limit);
        $items = ArticleResource::collection($paginationData);
        $items->appends($request->query());

        $allCategories = ArticleCategory::getTreeData();

        return view('admin.articles.index',[
            'items' => $items,
            'allCategories' => $allCategories
        ]);
    }

    /**
     * 添加
     * @return Application|Factory|View
     */
    public function store() {
        $allCategories = ArticleCategory::getTreeData();
        return view('admin.articles.store',[
            'allCategories' => $allCategories
        ]);
    }

    /**
     * 编辑
     * @param Article $article
     * @return Application|Factory|View
     */
    public function profile(Article $article) {
        $allCategories = ArticleCategory::getTreeData();
        return view('admin.articles.store', [
            'item' => $article,
            'allCategories' => $allCategories
        ]);
    }
}
