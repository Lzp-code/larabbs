<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic){
        //过滤config\purifier.php里面的user_topic_body页面元素过滤
        //（目前\views\topics\create_and_edit.blade.php使用的 simditor 编辑器可以
        //自动转译  <script>alert('存在 XSS 安全威胁！')</script>  这样的内容，
        //如果要测试此过滤功能，需要先注释掉 其页面中的section('styles') 和 @section('scripts')）
        $topic->body = clean($topic->body, 'user_topic_body');

        $topic->excerpt = make_excerpt($topic->body);
    }
}