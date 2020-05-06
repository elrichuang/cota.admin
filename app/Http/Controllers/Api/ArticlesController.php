<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit');
        $category_id = $request->get('category_id');
        if ($limit)
        {
            $paginationData = Article::orderBy('id','desc')->when($category_id, function ($query, $category_id) {
                return $query->where('category_id', $category_id);
            })->paginate($limit);
            $items = ArticleResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Article::when($category_id, function ($query, $category_id) {
                return $query->where('category_id', $category_id);
            })->all();
            $items = ArticleResource::collection($paginationData);
            $total = $paginationData->count();
        }

        return responseSuccess('文章列表',[
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article)
    {
        return responseSuccess('文章详细信息',new ArticleResource($article));
    }
}
