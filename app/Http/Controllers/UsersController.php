<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{

    public function __construct(){
        $this->middleware('auth',['except' => ['show']]);
    }


    public function show(User $user){
        return view('users.show',compact('user'));
    }

    public function edit(User $user){
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    //传参 UserRequest。这将触发表单请求类的自动验证机制，验证发生在 UserRequest 中，
    //并使用此文件中方法 rules() 定制的规则，只有当验证通过时，才会执行 控制器 update() 方法中的代码
    public function update(UserRequest $request, ImageUploadHandler $uploader,User $user){
        $this->authorize('update',$user);

        $data = $request->all();

        //如果有上传头像，用ImageUploadHandler的$uploader将头像文件上传，并获得上传的路径
        if($request->avatar){
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 416);
            if($result){
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}