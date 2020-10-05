<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BreachLocation extends Model
{

    protected $table = 'breach_locations';

    protected $fillable = [
        "id", "code", "name", "xcoord", "ycoord", "dykering", "longname", "vnk2"
    ];

    public function scenarios()
    {
        return $this->belongsToMany('App\Scenario', 'breach_location_scenario', 'breach_location_id', 'scenario_id');
    }
}
