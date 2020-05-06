<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    protected $modelClass;
    /**
     * 批量删除
     * @param Request $request
     * @return JsonResponse
     */
    public function destroyMany(Request $request)
    {
        try {
            $ids = $request->ids;
            $idsArray = explode(',', $ids);
            if (!count($idsArray)) {
                throw new Exception('参数错误');
            }
            foreach ($idsArray as $id) {
                if(!stringIsInt($id))
                {
                    throw new Exception('参数错误');
                }
            }

            $modelClassName = '\\App\\Models\\'.$this->modelClass;

            $modelClassName::whereIn('id',$idsArray)->delete();

            return responseSuccess('删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
