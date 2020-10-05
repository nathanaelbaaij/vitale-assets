<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news_posts';

    protected $fillable = [
        "id", "title", "message", "user_id", "news_category_id",
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\NewsCategory', 'news_category_id', 'id');
    }
}
