<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depth extends Model
{
    protected $table = 'depths';

    protected $fillable = [
        "id", "breach_location_id", "load_level_id", "water_depth"
    ];

    public function asset()
    {
        return $this->belongsTo('App\Asset', 'asset_id', 'id');
    }

    public function breachlocation()
    {
        return $this->belongsTo('App\BreachLocation', 'breach_location_id', 'id');
    }

    public function loadlevel()
    {
        return $this->belongsTo('App\LoadLevel', 'load_level_id', 'id');
    }
}
