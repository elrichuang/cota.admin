<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ArticleCategoryResource;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleCategoriesController extends Controller
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
        if ($limit)
        {
            $paginationData = ArticleCategory::orderBy('id','desc')->paginate($limit);
            $items = ArticleCategoryResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = ArticleCategory::all();
            $items = ArticleCategoryResource::collection($paginationData);
            $total = $paginationData->count();
        }

        return responseSuccess('分类列表',[
            'items' => $items,
            'total' => $total
        ]);
    }
}
