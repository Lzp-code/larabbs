<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Auth;
class User extends Authenticatable implements MustVerifyEmailContract
{
    //use以后，即可直接使用里面的方法
    use  MustVerifyEmailTrait;

    //这里的意思是给 Notifiable 这个 trait 中的 notify 方法起一个别名 laravelNotify
    //同时访问控制为受保护的（protected ）。
    use Notifiable {
        notify as protected laravelNotify;
    }

//    use Notifiable, MustVerifyEmailTrait;

    protected $fillable = [
        'name', 'email', 'password','introduction','avatar',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function topics(){
        return $this->hasMany(Topic::class);
    }

    //一个用户可以拥有多条评论
    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function notify($instance,$create_uid)
    {
//        var_dump($create_uid);
//        var_dump($instance);

//        var_dump($instance->reply->attributes);
//        var_dump($instance->reply->attributes->user_id);

//        $instance->reply->attributes->user_id = $create_uid;

//        var_dump($instance);
//        exit();

        // 如果话题创建者是当前用户，就不必通知了！
        if ($create_uid == Auth::id()) {
            return;
        }
        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }
        $this->laravelNotify($instance);
    }




}