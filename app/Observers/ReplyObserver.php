<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        $reply->topic->reply_count = $reply->topic->replies->count();
//        var_dump($reply);
//        var_dump($reply->topic);
//        var_dump($reply->topic->replies->count());

//		exit();

        $reply->topic->save();

//        var_dump($reply->topic);
//        var_dump($reply->topic->user_id);
//        exit();
        $create_uid = $reply->topic->user_id;


        // 给user的models传过去一个TopicReplied类和话题创建者的id
        $reply->user->notify(new TopicReplied($reply),$create_uid);// 通知话题作者有新的评论
    }

    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

}