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
     * @param Request $request
     * @return JsonResponse
     */
    public function ossPolicy(Request $request)
    {
        $id= config('oss.accessKeyId');          // 请填写您的AccessKeyId。
        $key= config('oss.accessKeySecret');     // 请填写您的AccessKeySecret。
        // $host的格式为 bucketname.endpoint，请替换为您的真实信息。
        $host = config('oss.host');
        $imgHost = config('oss.imgHost');
        // $callbackUrl为上传回调服务器的URL，请将下面的IP和Port配置为您自己的真实URL信息。
        $callbackUrl = config('oss.callbackUrl');
        $dir = config('oss.userDir');          // 用户上传文件时指定的前缀。

//        $callback_param = array('callbackUrl'=>$callbackUrl,
//            'callbackBody'=>'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}',
//            'callbackBodyType'=>"application/x-www-form-urlencoded");
//        $callback_string = json_encode($callback_param);
//
//        $base64_callback_body = base64_encode($callback_string);

        $endObj = Carbon::now()->addSeconds(30);
        $end = $endObj->timestamp;
        $expiration = $endObj->toIso8601ZuluString();//gmt_iso8601($end);


        //最大文件大小.用户可以自己设置
        $condition = [
            'content-length-range',
            0,
            1048576000
        ];
        $conditions[] = $condition;

        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
        $start = [
            'starts-with',
            '$key',
            $dir
        ];
        $conditions[] = $start;


        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessId'] = $id;
        $response['host'] = $host;
        $response['imgHost'] = $imgHost;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['callback'] = '';//$base64_callback_body;
        $response['dir'] = $dir;  // 这个参数是设置用户上传文件时指定的前缀。
        $response['key'] = '';//文件名

        return responseSuccess('OSS Policy',$response);
    }

    /**
     * 给阿里云服务器回调用的
     * @param Request $request
     */
    public function ossCallback(Request $request)
    {
        // 1.获取OSS的签名header和公钥url header
        $authorizationBase64 = "";
        $pubKeyUrlBase64 = "";
        /*
         * 注意：如果要使用HTTP_AUTHORIZATION头，你需要先在apache或者nginx中设置rewrite，以apache为例，修改
         * 配置文件/etc/httpd/conf/httpd.conf(以你的apache安装路径为准)，在DirectoryIndex index.php这行下面增加以下两行
            RewriteEngine On
            RewriteRule .* - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization},last]
         * */
        if (isset($_SERVER['HTTP_AUTHORIZATION']))
        {
            $authorizationBase64 = $_SERVER['HTTP_AUTHORIZATION'];
        }
        if (isset($_SERVER['HTTP_X_OSS_PUB_KEY_URL']))
        {
            $pubKeyUrlBase64 = $_SERVER['HTTP_X_OSS_PUB_KEY_URL'];
        }

        if ($authorizationBase64 == '' || $pubKeyUrlBase64 == '')
        {
            header("http/1.1 403 Forbidden");
            exit();
        }

        // 2.获取OSS的签名
        $authorization = base64_decode($authorizationBase64);

        // 3.获取公钥
        $pubKeyUrl = base64_decode($pubKeyUrlBase64);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pubKeyUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $pubKey = curl_exec($ch);
        if ($pubKey == "")
        {
            //header("http/1.1 403 Forbidden");
            exit();
        }

        // 4.获取回调body
        $body = file_get_contents('php://input');

        // 5.拼接待签名字符串
        $authStr = '';
        $path = $_SERVER['REQUEST_URI'];
        $pos = strpos($path, '?');
        if ($pos === false)
        {
            $authStr = urldecode($path)."\n".$body;
        }
        else
        {
            $authStr = urldecode(substr($path, 0, $pos)).substr($path, $pos, strlen($path) - $pos)."\n".$body;
        }

        // 6.验证签名
        $ok = openssl_verify($authStr, $authorization, $pubKey, OPENSSL_ALGO_MD5);
        if ($ok == 1)
        {
            header("Content-Type: application/json");
            $data = array("Status"=>"Ok");
            echo json_encode($data);
        }
        else
        {
            //header("http/1.1 403 Forbidden");
            exit();
        }
    }
}
