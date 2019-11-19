<?php

//将当前请求的路由器名称转换为css类名称，作用是允许我们针对某个页面做页面样式定制
function route_class(){
    return str_replace('.','-',Route::currentRouteName());
}