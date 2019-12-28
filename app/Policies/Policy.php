<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

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
