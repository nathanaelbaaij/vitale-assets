<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentPic extends Model
{
    protected $fillable = [
        'comment_id', 'image_url',
    ];
}
