<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PagesController extends Controller
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
            $paginationData = Page::orderBy('id','desc')->paginate($limit);
            $items = PageResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Page::all();
            $items = PageResource::collection($paginationData);
            $total = $paginationData->count();
        }

        return responseSuccess('页面列表',[
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Page $page
     * @return JsonResponse
     */
    public function show(Page $page)
    {
        return responseSuccess('页面详细信息',new PageResource($page));
    }
}
