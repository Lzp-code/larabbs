<?php

//将当前请求的路由器名称转换为css类名称，作用是允许我们针对某个页面做页面样式定制
function route_class(){
    return str_replace('.','-',Route::currentRouteName());
}

function category_nav_active($category_id){
    //active_class($condition, $activeClass = 'active', $inactiveClass = '')
    //指如果传参满足指定条件 ($condition) ，此函数将返回 $activeClass，否则返回 $inactiveClass。
//    if_route () - 判断当前对应的路由是否是指定的路由；
//    if_route_param () - 判断当前的 url 有无指定的路由参数。
//    if_query () - 判断指定的 GET 变量是否符合设置的值；
//    if_uri () - 判断当前的 url 是否满足指定的 url；
//    if_route_pattern () - 判断当前的路由是否包含指定的字符；
//    if_uri_pattern () - 判断当前的 url 是否含有指定的字符；
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}