<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SpuCategoryResource;
use App\Models\SpuCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpuCategoriesController extends Controller
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
            $paginationData = SpuCategory::orderBy('id','desc')->paginate($limit);
            $items = SpuCategoryResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = SpuCategory::all();
            $items = SpuCategoryResource::collection($paginationData);
            $total = $paginationData->count();
        }

        return responseSuccess('分类列表',[
            'items' => $items,
            'total' => $total
        ]);
    }
}
