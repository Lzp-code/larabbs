<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    //权限验证，此处 return true; ，意味所有权限都通过即可
    public function authorize()
    {
        return true;
    }

    //调用本文件后，会自动使用本方法验证方法
    public function rules()
    {
        return [
            'name'=>'required | between:3,25 | regex:/^[A-Za-z0-9\-\_]+$/ | unique:users,name,' . Auth::id(), //unique——name必须唯一，但是忽略此指定的id
            'email'=>'required | email',
            'introduction'=>'max:80',
            'avatar' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=208,min_height=208',
        ];
    }

    //调用本文件后，会自动使用上面的验证方法，并返回这里的验证信息
    public function messages()
    {
        return [
            'avatar.mimes' =>'头像必须是 jpeg, bmp, png, gif 格式的图片',
            'avatar.dimensions' => '图片的清晰度不够，宽和高需要 208px 以上',
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}