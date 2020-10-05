<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetProperty extends Model
{
    protected $table = 'asset_properties';

    protected $fillable = [
        'asset_id', 'name', 'value',
    ];
}
