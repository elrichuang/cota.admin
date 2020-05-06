<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SkuResource;
use App\Models\Sku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkusController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Sku $skus
     * @return JsonResponse
     */
    public function show(Sku $skus)
    {
        return responseSuccess('详细信息',new SkuResource($skus));
    }
}
