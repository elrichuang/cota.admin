<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\ImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('refresh.token:api');

        //$this->authorizeResource(User::class,'user');
    }

    public function store(ImageRequest $request, ImageUploadHandler $uploader, Image $image)
    {
        $member = auth('api')->user();

        $size = $request->type == 'avatar' ? 416 : 1024;
        $result = $uploader->save($request->image, Str::plural($request->type), $member->id, $size);

        $image->path = $result['path'];
        $image->type = $request->type;
        $image->member_id = $member->id;
        $image->save();

        return responseSuccess('图片上传成功',new ImageResource($image));
    }
}
