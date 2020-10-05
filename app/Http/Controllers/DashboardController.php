<?php

namespace App\Http\Controllers;

use App\Scenarios;
use Symfony\Component\HttpFoundation\Request;
use App\LoadLevel;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //get current scenario
        $scenario = Scenarios::getOrCreateUserScenario();

        $scenarioCategories = $scenario->categories()->get();
        $scenarioBreachLocations = $scenario->breachlocations()->get();
        $scenarioLoadLevel = $scenario->loadlevel;

        //counts assets on the map
        $scenarioAssetsCount = 0;
        $senarioCategories = $scenario->categories()->withCount('assets')->get();
        foreach ($senarioCategories as $senarioCategory) {
            $scenarioAssetsCount += $senarioCategory->assets_count;
            if ($catChilds = $senarioCategory->children()->withCount('assets')->get()) {
                foreach ($catChilds as $catChild) {
                    $scenarioAssetsCount += $catChild->assets_count;
                }
            }
        }

        return view(
            'dashboard',
            compact('scenario', 'scenarioAssetsCount', 'scenarioCategories', 'scenarioBreachLocations', 'scenarioLoadLevel')
        );
    }
}
