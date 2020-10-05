<?php

namespace App\Http\Controllers;

use App\Scenarios;
use Illuminate\Http\Request;
use App\CategoryScenario;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ScenariosController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:scenario-list');
        $this->middleware('permission:scenario-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:scenario-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:scenario-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $scenarios = Scenarios::all();
        $currentUserScenario = Auth::user()->scenario_id;
        return view('scenarios.index', compact('scenarios', 'currentUserScenario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('scenarios.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:255|unique:scenarios',
        ]);

        $scenario = Scenarios::create([
            'name' => request('name'),
            'loadlevel_id' => 2
        ]);

        if ($scenario) {
            $user = User::find(Auth::user()->id);
            $user->update(['scenario_id' => $scenario->id]);
        }

        session()->flash('message', 'Scenario "' . request('name') . '" is aangemaakt.');
        return redirect()->route('scenarios.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Scenarios $scenario
     * @return \Illuminate\Http\Response
     */
    public function show(Scenarios $scenario)
    {
        $currentUserScenario = Auth::user()->scenario_id;
        return view("scenarios.show", compact('scenario', 'currentUserScenario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Scenarios $scenario
     * @return \Illuminate\Http\Response
     */
    public function edit(Scenarios $scenario)
    {
        return view('scenarios.edit', compact('scenario'));
    }

    /**
     * @param Request $request
     * @param Scenarios $scenario
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Scenarios $scenario)
    {
        $this->validate($request, [
            'name' => [
                Rule::unique('scenarios')->ignore($scenario->id),
                'required',
                'min:3',
                'max:255',
            ],
        ]);

        $scenario->update([
            'name' => request('name'),
        ]);

        session()->flash('message', 'Scenario "' . request('name') . '" is gewijzigd.');
        return redirect()->route('scenarios.index');
    }

    /**
     * @param Scenarios $scenario
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Scenarios $scenario)
    {
        if ($amountOfUsers = $scenario->users->count() > 0) {
            return redirect()->route('scenarios.show', ['id' => $scenario->id])
                ->with('errors', 'Scenario is nog gekoppeld met ' . $amountOfUsers . ' andere gebruiker(s).');
        }

        $scenario->delete();
        session()->flash('message', 'Scenario "' . $scenario->name . '" is verwijderd.');
        return redirect()->route('scenarios.index');
    }

    /**
     * @param Scenarios $scenario
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchScenario(Scenarios $scenario)
    {
        $user = User::find(Auth::user()->id);
        $user->update(['scenario_id' => $scenario->id]);

        session()->flash('message', 'Scenario "' . $scenario->name . '" is nu het huidige scenario.');
        return redirect()->route('scenarios.index');
    }

    /**
     * Try to find the current selected breachlocations
     * @return mixed breachlocations or null
     */
    public function getSelectedBreachLocations()
    {
        //get or create user scenario
        $scenario = Scenarios::getOrCreateUserScenario();
        //return selected breachlocations

        //create breachlocation ids array
        $breachLocationsArray = [];
        foreach ($scenario->breachlocations as $breachLocation) {
            $breachLocationsArray[] = $breachLocation->id;
        }

        //return array
        return $breachLocationsArray;
    }

    /**
     * Toggle breachlocation in database
     * @param Request $request
     */
    public function toggleBreachLocations(Request $request)
    {
        //set breachlocatios array
        $breachLocationIds = $request->get('ids');

        //get or create user scenario
        $scenario = Scenarios::getOrCreateUserScenario();

        //toggle the breachlocation
        $scenario->breachlocations()->sync($breachLocationIds);
    }

    /**
     * clear all breachlocation scenario selections in db
     */
    public function clearBreachLocations()
    {
        $scenario = Scenarios::getOrCreateUserScenario();
        $scenario->breachlocations()->detach();
    }

    /**
     * Get all the category ids from the current scenario
     * @return null or category ids
     */
    public function getSelectedCategories()
    {
        //get or create user scenario
        $scenario = Scenarios::getOrCreateUserScenario();

        if (!$selectedCategories = $scenario->categories()->select('categories.id')->get()) {
            return null;
        }

        //subtract the ids from the query and put them into an array
        $selectedCategoriesArray = [];
        foreach ($selectedCategories as $selectedCategory) {
            $selectedCategoriesArray[] = $selectedCategory->id;
        }

        return $selectedCategoriesArray;
    }

    /**
     * Toggle categories in the database
     * @param Request $request
     */
    public function toggleCategories(Request $request)
    {
        //set and convert to int and boolean
        $categoryId = (int)$request->get('id');
        $categoryState = filter_var($request->get('state'), FILTER_VALIDATE_BOOLEAN);

        //get or create user scenario
        $scenario = Scenarios::getOrCreateUserScenario();

        //show category in scenario or hide
        if ($categoryState) {
            //try to find it
            $categoryScenario = CategoryScenario::where('category_id', $categoryId)->first();
            //add category to scenario
            if (!$categoryScenario) {
                $scenario->categories()->attach($categoryId);
            }
        } else {
            //remove category from scenario
            $scenario->categories()->detach($categoryId);
        }
    }

    /**
     * clear all categories in current scenario
     */
    public function clearCategories()
    {
        $scenario = Scenarios::getOrCreateUserScenario();
        $scenario->categories()->detach();
    }


    /**
     * Get the current loadlevel from the scenario
     * @return mixed
     */
    public function getSelectedLoadLevel()
    {
        $scenario = Scenarios::getOrCreateUserScenario();
        return $scenario->loadlevel_id;
    }

    /**
     * Switch from the loadlevel
     * @param Request $request
     */
    public function switchLoadLevel(Request $request)
    {
        $scenario = Scenarios::getOrCreateUserScenario();
        $loadLevel = (int)$request->get('id');

        $scenario->update([
            'loadlevel_id' => $loadLevel,
        ]);
    }

    /**
     * returns all assets names in current scenario
     */
    public function getScenarioAssets()
    {
        $scenario = Scenarios::getOrCreateUserScenario();
        $categories = $scenario->categories()->get();

        $scenarioAssetsArray = [];
        foreach ($categories as $category) {
            foreach ($category->assets as $asset) {
                $scenarioAssetsArray[] = [
                    'asset' => [
                        'name' => $asset->name,
                        'self' => route('assets.show', $asset->id),
                    ],
                    'category' => [
                        'name' => $asset->category->name,
                        'symbol' => $asset->category->symbol,
                        'self' => route('categories.show', $asset->category->id),
                    ],
                    'state_color' => $asset->computeState(), //status
                    'water_depth' => $asset->getCurrentWaterDepth(),
                    'type' => $asset->geometry_type,
                    'threshold_correction' => $asset->threshold_correction,
                ];
            }

            if ($subCategories = $category->children()->get()) {
                foreach ($subCategories as $subCategory) {
                    foreach ($subCategory->assets as $subCatAsset) {
                        $scenarioAssetsArray[] = [
                            'name' => $subCatAsset->name,
                            'category' => [
                                'name' => $subCatAsset->category->name,
                                'symbol' => $subCatAsset->category->symbol,
                                'self' => route('categories.show', $subCatAsset->category->id),
                            ],
                            'state_color' => $subCatAsset->computeState(), //status
                            'water_depth' => $subCatAsset->getCurrentWaterDepth(),
                            'type' => $subCatAsset->geometry_type,
                            'threshold_correction' => $subCatAsset->threshold_correction,
                        ];
                    }
                }
            }
        }

        return response()->json($scenarioAssetsArray);
    }
}
