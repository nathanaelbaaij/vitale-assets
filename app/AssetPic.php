<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetPic extends Model
{
    protected $table = 'asset_pics';

    protected $fillable = [
        'asset_id', 'image',
    ];
}
