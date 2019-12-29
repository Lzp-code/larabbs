<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    //使用授权策略的 策略过滤器 机制来实现统一授权的目的。我们只需在策略中定义一个 before() 方法。
    //before 方法会在策略中其它所有方法之前执行，这样提供了一种全局授权的方案。
    //代码生成器所生成的授权策略，都会统一继承 App\Policies\Policy 基类
    //这样我们只需要在基类的 before() 方法里做下角色权限判断即可作用到所有的授权类：
    public function before($user, $ability)
    {
        // 如果用户拥有管理内容的权限的话，即授权通过
        if ($user->can('manage_contents')) {
            return true;
        }
    }

}
