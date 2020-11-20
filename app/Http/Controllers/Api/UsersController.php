<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * @param UserRequest $request
     * @param name string  姓名
     * @param password string  密码
     * @param verification_key string  发送验证码返回key
     * @param verification_code string  验证码
     * @return UserResource
     */
    public function store(UserRequest $request)
    {
//        dd(1);
        $verifyData = \Cache::get($request->verification_key);
//        dd($verifyData,$request->all());
        if (!$verifyData) {
            abort(403, '验证码已失效');
        }
//dd();
        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            throw new AuthenticationException('验证码错误');
        }
        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => $request->password,
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return new UserResource($user);
    }
}
