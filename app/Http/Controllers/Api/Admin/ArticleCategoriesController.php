<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\ArticleCategoryStoreRequest;
use App\Http\Requests\Admin\ArticleCategoryUpdateRequest;
use App\Http\Resources\ArticleCategoryResource;
use App\Models\ArticleCategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleCategoriesController extends Controller
{
    protected $modelClass = 'ArticleCategory';

    public function __construct()
    {
        $this->middleware('refresh.token:admin');

        //$this->authorizeResource(User::class,'user');
    }

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

        return responseSuccess('信息列表',[
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleCategoryStoreRequest $request
     * @return JsonResponse
     */
    public function store(ArticleCategoryStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();

            $entity = ArticleCategory::create([
                'user_id' => $user->id,
                'parent_id' => $request->parent_id,
                'title' => $request->title,
                'num_sort' => $request->num_sort,
            ]);

            $entity->save();

            return responseSuccess('创建成功', new ArticleCategoryResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ArticleCategory $articleCategory
     * @return JsonResponse
     */
    public function show(ArticleCategory $articleCategory)
    {
        return responseSuccess('详细信息',new ArticleCategoryResource($articleCategory));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleCategoryUpdateRequest $request
     * @param ArticleCategory $articleCategory
     * @return JsonResponse
     */
    public function update(ArticleCategoryUpdateRequest $request, ArticleCategory $articleCategory)
    {
        try {
            $articleCategory->parent_id = $request->parent_id;
            $articleCategory->title = $request->title;
            $articleCategory->num_sort = $request->num_sort;
            $articleCategory->save();

            return responseSuccess('更新成功', new ArticleCategoryResource($articleCategory));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ArticleCategory $articleCategory
     * @return JsonResponse
     */
    public function destroy(ArticleCategory $articleCategory)
    {
        try {
            $articleCategory->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
