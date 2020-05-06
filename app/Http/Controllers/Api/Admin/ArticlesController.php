<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\ArticleStoreRequest;
use App\Http\Requests\Admin\ArticleUpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ArticlesController extends Controller
{
    protected $modelClass = 'Article';

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
            $paginationData = Article::orderBy('on_top_at','desc')
                ->orderBy('num_sort','desc')
                ->orderBy('id','desc')->paginate($limit);
            $items = ArticleResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Article::all();
            $items = ArticleResource::collection($paginationData);
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
     * @param  ArticleStoreRequest  $request
     * @return JsonResponse
     */
    public function store(ArticleStoreRequest $request)
    {
        try {
            $user = auth('admin')->user();
            $on_top_at = null;
            if ($request->on_top)
            {
                $on_top_at = now();
            }
            $recommend_at = null;
            if ($request->recommend)
            {
                $recommend_at = now();
            }
            $entity = Article::create([
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'author' => $request->author,
                'summary' => $request->summary,
                'content' => $request->get('content'),
                'thumb' => $request->thumb,
                'num_like' => $request->num_like,
                'num_view' => $request->num_view,
                'num_sort' => $request->num_sort,
                'on_top_at' => $on_top_at,
                'recommend_at' => $recommend_at,
                'published_at' =>$request->published_at,
            ]);

            $entity->save();

            return responseSuccess('创建成功', new ArticleResource($entity));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article)
    {
        return responseSuccess('详细信息',new ArticleResource($article));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleUpdateRequest $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
        try {
            $on_top_at = null;
            if (boolval($request->on_top))
            {
                $on_top_at = Carbon::now()->toDateTimeString();
            }

            $recommend_at = null;
            if ($request->recommend)
            {
                $recommend_at = Carbon::now()->toDateTimeString();
            }
            $article->category_id = $request->category_id;
            $article->title = $request->title;
            $article->sub_title = $request->sub_title;
            $article->author = $request->author;
            $article->summary = $request->summary;
            $article->content = $request->get('content');
            $article->thumb = $request->get('thumb');
            $article->num_sort = $request->num_sort;
            $article->num_like = $request->num_like;
            $article->num_view = $request->num_view;
            $article->on_top_at = $on_top_at;
            $article->recommend_at = $recommend_at;
            $article->published_at = $request->published_at;
            $article->save();

            return responseSuccess('更新成功', new ArticleResource($article));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article)
    {
        try {
            $article->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
