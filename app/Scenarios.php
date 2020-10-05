<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class Scenarios extends Model
{
    protected $table = 'scenarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'loadlevel_id',
    ];

    public function breachlocations()
    {
        return $this->belongsToMany('App\BreachLocation', 'breach_location_scenario', 'scenario_id', 'breach_location_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_scenario', 'scenario_id', 'category_id')->using('App\CategoryScenario');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'scenario_id', 'id');
    }

    public function loadlevel()
    {
        return $this->belongsTo('App\LoadLevel', 'loadlevel_id', 'id');
    }

    public function assets()
    {
        return $this->hasManyThrough(
            'App\Asset',
            'App\CategoryScenario',
            'scenario_id',
            'category_id',
            'id',
            'category_id'
        );
    }

    /**
     * Find the user scenario if not create one and return it
     * @return mixed
     */
    public static function getOrCreateUserScenario()
    {
        //find user
        $user = User::find(Auth::user()->id);

        //has user a scenario?
        if ($user->scenario_id == null) {
            //create scenario
            $scenario = self::create([
                'name' => Carbon::now()->format('YmdHis') . '_' . $user->id,
                'loadlevel_id' => 2, //TP loadlevel
            ]);
            //update user
            $user->update(['scenario_id' => $scenario->id]);
        } else {
            $scenario = self::find($user->scenario_id);
        }
        //set loadlevel session
        request()->session()->put('loadlevel', $scenario->loadlevel_id);
        return $scenario;
    }
}
