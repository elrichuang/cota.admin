<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SpuResource;
use App\Models\Spu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpusController extends Controller
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
        $keyword = $request->get('keyword');
        $category_id = $request->get('category_id');

        $paginationData = Spu::when($category_id, function($query, $category_id){
            return $query->where('category_id',$category_id);
        })->when($keyword, function ($query, $keyword) {
                return $query->where(function($query) use ($keyword){
                    $query->whereIn('id',function($query) use ($keyword){
                        $query->select('spu_id')
                            ->from('skus')
                            ->where('name', 'like','%'.$keyword.'%')
                            ->orWhere('sku_no', 'like','%'.$keyword.'%');
                    })->orWhere('name', 'like','%'.$keyword.'%')->orWhere('spu_no', 'like','%'.$keyword.'%');
                });
            })->orderBy('on_top_at','desc')
            ->orderBy('num_sort','desc')
            ->orderBy('id','desc');

        if ($limit) {
            $paginationData = $paginationData->paginate($limit);
        }else {
            $paginationData = $paginationData->get();
        }

        $items = SpuResource::collection($paginationData);
        $paginationDataArray = $paginationData->toArray();
        if ($limit)
        {
            $total = $paginationDataArray['total'];
        }else {
            $total = $paginationData->count();
        }

        return responseSuccess('信息列表',[
            'items' => $items,
            'total' => $total
        ]);
    }
}
