<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryScenario extends Pivot
{
    protected $table = 'category_scenario';

    public function scenario()
    {
        return $this->belongsTo('App\Scenario');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function assets()
    {
        return $this->hasManyThrough('App\Assets', 'App\Category');
    }
}
