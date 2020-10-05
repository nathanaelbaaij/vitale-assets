<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'asset_id', 'user_id' ,'message', 'imageFile',
    ];
}
