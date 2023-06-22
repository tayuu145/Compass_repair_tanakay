<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments()
    {
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategory()
    {
        // リレーションの定義
        return $this->belongsTo('App\Models\Categories\SubCategory', 'sub_category');
    }

    public function likes()
    {
        // 　　　　　　　　　　　　　　　　　　　　　　　　　　↓第二引数でカラムを指定。指定してないと外部キー命名規則のカラム名で探しに行く。
        return $this->hasMany('App\Models\Posts\Like', 'like_post_id');
    }

    // コメント数
    public function commentCounts($post_id)
    {
        return Post::with('postComments')->find($post_id)->postComments();
    }
}
