<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function root(){

//        var_dume(\Auth::user()->hasVerifiedEmail()); //验证下当前登录的用户是否是已经认证过
        return view('pages.root');
    }
}