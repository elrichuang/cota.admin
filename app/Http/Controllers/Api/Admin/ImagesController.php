<?php

namespace App\Http\Controllers\Api\Admin;

use App\Handlers\ImageUploadHandler;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('refresh.token:admin');

        //$this->authorizeResource(User::class,'user');
    }

    /**
     * 图片上传本地服务器
     * @param ImageRequest $request
     * @param ImageUploadHandler $uploader
     * @param Image $image
     * @return JsonResponse
     */
    public function store(ImageRequest $request, ImageUploadHandler $uploader, Image $image)
    {
        $user = auth('admin')->user();

        $size = $request->type == 'avatar' ? 416 : 1024;
        $result = $uploader->save($request->image, Str::plural($request->type), $user->id, $size);

        $image->path = $result['path'];
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();

        return responseSuccess('图片上传成功',new ImageResource($image));
    }

    /**
     * 获取OSS直传用Policy
     * @return JsonResponse
     */
    public function ossPolicy()
    {
        $disk = Storage::disk('oss');

        /**
         * 1. 前缀如：'images/'
         * 2. 回调服务器 url
         * 3. 回调自定义参数，oss 回传应用服务器时会带上
         * 4. 当前直传配置链接有效期
         */
        $config = $disk->signatureConfig($prefix = config('oss.userDir'), $callBackUrl = config('oss.callbackUrl'), $customData = [], $expire = 30);

        $config = json_decode($config);
        $config->imgHost = config('oss.imgHost');

        return responseSuccess('OSS Policy',$config);
    }

    /**
     * 给阿里云服务器回调用的
     * @param Request $request
     * @return JsonResponse
     */
    public function ossCallback(Request $request)
    {
        $disk = Storage::disk('oss');
        // 验签，就是如此简单
        // $verify 验签结果，$data 回调数据
        list($verify, $data) = $disk->verify();
        // [$verify, $data] = $disk->verify(); // php 7.1 +

        if (!$verify) {
            // 验证失败处理，此时 $data 为验签失败提示信息
        }

        // 注意一定要返回 json 格式的字符串，因为 oss 服务器只接收 json 格式，否则给前端报 CallbackFailed
        return response()->json($data);
    }
}
