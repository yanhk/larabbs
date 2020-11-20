<?php

namespace App\Http\Controllers\Api;

use Illuminate\Auth\AuthenticationException;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{

    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captchaData = \Cache::get($request->captcha_key);
        if (!$captchaData) {
            abort(403, '图片验证码已失效');
        }
//        dd($captchaData['code'], $request->captcha_code);
        if (!hash_equals(strtolower($captchaData['code']), strtolower($request->captcha_code))) {
            // 验证错误就清除缓存
            \Cache::forget($request->captcha_key);
            throw new AuthenticationException('验证码错误');
        }
        $phone = $captchaData['phone'];


        $phone = $request->phone;
//dd($request->all(), $phone,app()->environment('production'));
        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            $code = random_int(1000,9999);
            try {
                $result = $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data' => [
                        'code' => $code
                    ],
                ]);
            }catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception){
                $message = $exception->getException('aliyun')->getMessage();
                dump($message);
//            abort(501, $message ?: '短信发送异常');
            }
        }

//        dd(Str::random(15));
        $key = 'verificationCode_'.Str::random(15);
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码 5 分钟过期。
        $a = \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
//dd($a);
        return response()->json([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
