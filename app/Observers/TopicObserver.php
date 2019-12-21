<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;

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

        // 生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        //app() 允许我们使用 Laravel 服务容器 ，此处我们用来生成 SlugTranslateHandler 实例。
        if(!$topic->slug){
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }

    }


    //型监控器的 saved() 方法对应 Eloquent 的 saved 事件，
    //此事件发生在创建和编辑时、数据入库以后。在 saved() 方法中调用，
    //确保了我们在分发任务时，$topic->id 永远有值。

    //要使用对垒是，将.env里面的QUEUE_CONNECTION=sync改为redis
    public function saved(Topic $topic)
    {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        // 推送任务到队列
        if ( ! $topic->slug) {
            dispatch(new TranslateSlug($topic));
        }
    }


    //当话题被删除的时，删除此话题下所有的回复
    public function deleted(Topic $topic){
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }


}