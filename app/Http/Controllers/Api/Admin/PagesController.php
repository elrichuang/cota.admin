<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\PageStoreRequest;
use App\Http\Requests\Admin\PageUpdateRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PagesController extends Controller
{
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
            $paginationData = Page::orderBy('id','desc')->paginate($limit);
            $items = PageResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Page::all();
            $items = PageResource::collection($paginationData);
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
     * @param PageStoreRequest $request
     * @return JsonResponse
     */
    public function store(PageStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();

            $entity = Page::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'content' => $request->get('content'),
            ]);

            $entity->save();

            return responseSuccess('创建成功', new PageResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Page $page
     * @return JsonResponse
     */
    public function show(Page $page)
    {
        return responseSuccess('详细信息',new PageResource($page));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PageUpdateRequest $request
     * @param Page $page
     * @return JsonResponse
     */
    public function update(PageUpdateRequest $request, Page $page)
    {
        try {

            $page->title = $request->title;
            $page->content = $request->get('content');
            $page->save();

            return responseSuccess('更新成功', new PageResource($page));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Page $page
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Page $page)
    {
        try {
            $page->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
