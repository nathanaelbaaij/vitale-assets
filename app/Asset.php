<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Asset
 * @package App
 *
 * Database fields
 * @property int id
 * @property string name
 * @property string description
 * @property \App\Category category
 * @property string geometry_type
 * @property string geometry_coordinates
 * @property float threshold_correction
 *
 */
class Asset extends Model
{
    private $currentWaterDepth;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'category_id', 'geometry_type', 'geometry_coordinates', 'threshold', 'threshold_correction',
    ];

    public function category()
    {
        return $this->belongsTo("App\Category", "category_id", "id");
    }

    public function properties()
    {
        return $this->hasMany("App\AssetProperty", "asset_id", "id");
    }

    public function depths()
    {
        return $this->hasMany('App\Depth', 'asset_id', 'id');
    }

    public function cascadeFrom()
    {
        return $this->hasMany('App\Cascade', 'asset_id_from', 'id');
    }

    public function cascadeTo()
    {
        return $this->hasMany('App\Cascade', 'asset_id_to', 'id');
    }

    public function picture()
    {
        return $this->hasOne("App\AssetPic", "asset_id", "id");
    }

    /**
     * Calculate the real threshold
     * Find the category threshold and add the correction to it.
     * @return mixed
     */
    public function getThresholdRealAttribute()
    {
        //define
        $categoryThreshold = $this->category()->first()->threshold;
        $assetThresholdCorrection = $this->threshold_correction;

        //calculate
        return $categoryThreshold + $assetThresholdCorrection;
    }

    public function getGeometryAttribute()
    {
        return "{'type':'$this->geometry_type','coordinates':$this->geometry_coordinates}";
    }

    /**
     * return the current waterdepth
     * @return mixed
     */
    public function getCurrentWaterDepth()
    {
        return $this->currentWaterDepth;
    }

    /**
     * set the current waterdepth
     * @param $currentWaterDepth
     */
    public function setCurrentWaterDepth($currentWaterDepth)
    {
        $this->currentWaterDepth = $currentWaterDepth;
    }

    /**
     * @param $breachLocation
     * @param int $loadLevel
     * @return int|null
     */
    public function computeAssetState($breachLocation, $loadLevel)
    {
        if (!$breachLocation) {
            return '#B4B0AA';
        }
        $scenario_depth = $this->depths()->where('breach_location_id', '=', $breachLocation)
            ->where('load_level_id', '=', $loadLevel)->first();
        //check if there are results
        if ($scenario_depth) {
            //define waterdepth and real threshold
            $this->setCurrentWaterDepth($scenario_depth->water_depth);

            //calculate the threshold correction
            $thresholdReal = $this->threshold_real;

            //check if thresholdreal -0.2 isnt a negative number
            if (($thresholdReal - 0.2) < 0) {
                $thresholdReal = 0;
            }

            $isStateRed = ($thresholdReal + 0.2) <= $this->getCurrentWaterDepth();
            $isStateGreen = $this->getCurrentWaterDepth() <= $thresholdReal;

            //calculate the state
            if ($isStateRed) {
                return 'red';
            } elseif ($isStateGreen) {
                return 'green';
            }
            return 'orange';
        }
        //cant find anything or breachlocation / loadlevel isn't set
        return '#B4B0AA';
    }

    /**
     * @return null|string
     */
    public function computeState()
    {
        $scenario = Scenarios::getOrCreateUserScenario();
        $loadLevel = $scenario->loadlevel_id;
        $breachLocations = $scenario->breachlocations()->get();

        //no breachlocations selected
        if (!$breachLocations) {
            return '#B4B0AA';
        }

        $breachLocationsArray = [];
        foreach ($breachLocations as $breachLocation) {
            $breachLocationsArray[] = $breachLocation->id;
        }

        $scenarioDepths = $this->depths()->whereIn('breach_location_id', $breachLocationsArray)
            ->where('load_level_id', '=', $loadLevel)->get();

        //check if there are results
        if ($scenarioDepths->count() > 0) {
            //get the highest waterdepth
            $highestWaterDepth = $this->calculateHighestWaterDepth($scenarioDepths);

            //define waterdepth and real threshold
            $this->setCurrentWaterDepth($highestWaterDepth);

            //calculate the threshold correction
            $thresholdReal = $this->threshold_real;

            //check if thresholdreal -0.2 isnt a negative number
            if (($thresholdReal - 0.2) < 0) {
                $thresholdReal = 0;
            }

            $isStateRed = ($thresholdReal + 0.2) <= $this->getCurrentWaterDepth();
            $isStateGreen = $this->getCurrentWaterDepth() <= $thresholdReal;

            //calculate the state
            if ($isStateRed) {
                return 'red';
            } elseif ($isStateGreen) {
                return 'green';
            }
            return 'orange';
        }

        //cant find anything or breachlocation / loadlevel isn't set
        return '#B4B0AA';
    }

    /**
     * @param $scenarioDepths
     * @return int
     */
    public function calculateHighestWaterDepth($scenarioDepths)
    {
        $highestWaterDepth = 0;
        foreach ($scenarioDepths as $scenarioDepth) {
            if ($scenarioDepth->water_depth > $highestWaterDepth) {
                $highestWaterDepth = $scenarioDepth->water_depth;
            }
        }
        return $highestWaterDepth;
    }

    /**
     * @return null
     */
    public function getImageAttribute()
    {
        $image = null;
        if ($this->picture) {
            $image = asset('uploads/assets/' . $this->picture->image);
        }
        return $image;
    }
}
