<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoadLevel extends Model
{
    protected $table = 'load_levels';

    protected $fillable = [
        "id", "code", "name", "description"
    ];

    public function scenarios()
    {
        return $this->hasMany('App\Scenarios', 'loadlevel_id', 'id');
    }
}
