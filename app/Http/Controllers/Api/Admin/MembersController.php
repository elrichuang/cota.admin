<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Admin\MemberStoreRequest;
use App\Http\Requests\Admin\MemberUpdateRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    protected $modelClass = 'Member';

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
            $paginationData = Member::orderBy('id','desc')->paginate($limit);
            $items = MemberResource::collection($paginationData);
            $paginationDataArray = $paginationData->toArray();
            $total = $paginationDataArray['total'];
        }else{
            $paginationData = Member::all();
            $items = MemberResource::collection($paginationData);
            $total = $paginationData->count();
        }

        return responseSuccess('会员列表',[
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Member  $member
     * @return JsonResponse
     */
    public function show(Member $member)
    {
        return responseSuccess('会员信息',new MemberResource($member));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  MemberStoreRequest  $request
     * @return JsonResponse
     */
    public function store(MemberStoreRequest $request)
    {
        try {
            $item = Member::create([
                'name' => $request->name,
                'avatar' => $request->avatar,
                'email' => $request->email,
                'nickname' => $request->nickname,
                'phone' => $request->phone,
                'sex' => $request->sex,
                'password' => bcrypt($request->password),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d'),
                'hour' => date('H'),
                'minute' => date('i')
            ]);

            $item->save();

            return responseSuccess('会员创建成功', new MemberResource($item));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MemberUpdateRequest $request
     * @param Member $member
     * @return JsonResponse
     */
    public function update(MemberUpdateRequest $request, Member $member)
    {
        try {
            $member->name = $request->name;
            $member->nickname = $request->nickname;
            $member->email = $request->email;
            $member->phone = $request->phone;
            $member->avatar = $request->avatar;
            $member->sex = $request->sex;
            $member->password = bcrypt($request->password);
            $member->save();

            return responseSuccess('会员更新成功', new MemberResource($member));
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Member $member
     * @return JsonResponse
     */
    public function destroy(Member $member)
    {
        try {
            $member->delete();

            return responseSuccess('会员删除成功');
        }catch (Exception $exception) {
            return responseFail($exception->getMessage());
        }
    }
}
