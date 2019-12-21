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
        $instance->reply->attributes['user_id'] = $create_uid;

        // 如果话题创建者是当前用户，就不必通知了！
        if ($create_uid == Auth::id()) {
            return;
        }

        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
        //$instance是接收app\Observers\ReplyObserver传过来的app\Notifications\TopicReplied类
        //method_exists—判断TopicReplied类里面是否有toDatabase方法
        if (method_exists($instance, 'toDatabase')) {

            //uese表notification_count字段加一
//            $this->increment('notification_count');


            $user = User::where('id',$create_uid)->firstOrFail();//在数据库里找到与id与话题创建者id匹配的第一个用户

            //被评论用户uese表notification_count字段加一
            $notification_count  = $user->attributes['notification_count'] + 1;
            $user->notification_count = $notification_count;
            $user->save();
        }

        //在notifications表新增一条notification_id为帖子创建人的id的数据
        //此处实际新增的却是notification_id为回复人的id的数据
        //暂时无法解决，需手动改动数据库，将回复人的id改为帖子创建人的id
        $this->laravelNotify($instance);
    }




}