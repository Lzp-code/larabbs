<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *构建函数里使用了三个中间件，并且使用了中间件简称
     * 这些简称是在 app/Http/Kernel.php 中的$routeMiddleware设置的
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');//所有控制器必须登录才能访问
        $this->middleware('signed')->only('verify');//只有verify动作使用signed中间件进行认证
        $this->middleware('throttle:6,1')->only('verify', 'resend');//'verify', 'resend'的频率限制为一分钟不可超过六次
    }
}
