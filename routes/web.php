<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    //Account
    Route::put('/account/{id}', 'AccountController@update')->name('account.update');

    //API
    Route::get('/api/assets', 'Api\AssetsAPIController@index');
    Route::get('/api/assets/{asset}', 'Api\AssetsAPIController@show');

    Route::get('/api/breachlocations', 'Api\BreachLocationsAPIController@index');
    Route::get('/api/breachlocations/{breachLocation}', 'Api\BreachLocationsAPIController@show');

    Route::get('/api/categories', 'Api\CategoriesAPIController@index');
    Route::get('/api/categories/{category}', 'Api\CategoriesAPIController@show');

    Route::get('api/news', 'Api\NewsAPIController@index');
    Route::get('api/news/{newspost}', 'Api\NewsAPIController@show');

    //Assets
    Route::resource('assets', 'AssetsController', ['except' => ['show']]);
    Route::get('/assets/import', 'AssetImportController@create')->name('assets.import');
    Route::post('/assets/import', 'AssetImportController@store')->name('assets.import.store');
    Route::get('/assets/{id}', 'AssetsController@show')->name('assets.show')->where('id', '[0-9]+');
    Route::post('/assets/{id}/comment', 'AssetsController@addComment')->name('asset.addComment')->where('id', '[0-9]');

    Route::get('/assets/floatscenarios', 'AssetsController@assetFloatScenarios')->name('assets.floatscenarios');

    Route::delete('/assets/{assetId}/delete/{propId}', 'PropertiesController@destroy');

    //Categories
    Route::resource('categories', 'CategoriesController', ['except' => ['show']]);
    Route::get('/categories/{id}', 'CategoriesController@show')->name('categories.show')->where('id', '[0-9]+');
    Route::get('/categories/search', 'CategoriesController@search')->name('categories.search');
    Route::get('/categories/threshold', 'CategoriesController@getThresholdByAssetId')->name('categories.threshold');

    //Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    //Invite
    Route::get('invites', 'InviteController@index')->name('invites.index');
    Route::get('invites/create', 'InviteController@create')->name('invites.create');
    Route::post('invites', 'InviteController@process')->name('process');
    Route::delete('invites/{invite}', 'InviteController@destroy')->name('invites.destroy');

    //Logbook
    Route::get('/logbook/revert/{id}', ['uses' => 'LogbookController@revert', 'as' => 'logbook.action.revert']);
    Route::get('/logbook/delete/{id}', ['uses' => 'LogbookController@remove', 'as' => 'logbook.action.delete']);

    //Map
    Route::get('/map', 'MapController@index')->name('map');

    //Profile
    Route::resource('profile', 'ProfileController', ['except' => ['']]);

    //Users avatar
    Route::post('/user/avatar', 'AvatarController@store')->name('user.avatar.store');
    Route::get('/user/avatar/{id}', ['uses' => 'AvatarController@update', 'as' => 'user.avatar.update']);

    /*
     * Resources
    */
    Route::resources([
        'users' => 'UsersController',
        'roles' => 'RolesController',
        'breaches' => 'BreachLocationsController',
        'properties' => 'PropertiesController',
        'news' => 'NewsController',
        'loadlevels' => 'LoadLevelsController',
        'logbook' => 'LogbookController',
        'cascades' => 'CascadesController',
        'consequences' => 'ConsequencesController',
        'scenarios' => 'ScenariosController'
    ]);

    //Scenario
    Route::get('/scenarios/switch/{scenario}', 'ScenariosController@switchScenario')->name('scenario.switch');

    //Breachlocations
    Route::get('/scenario/breach/get', 'ScenariosController@getSelectedBreachLocations')->name('scenario.breach.get');
    Route::get('/scenario/breach/toggle', 'ScenariosController@toggleBreachLocations')->name('scenario.breach.toggle');
    Route::get('/scenario/breach/clear', 'ScenariosController@clearBreachLocations')->name('scenario.breach.clear');

    //Categories
    Route::get('/scenario/category/get', 'ScenariosController@getSelectedCategories')->name('scenario.category.get');
    Route::get('/scenario/category/toggle', 'ScenariosController@toggleCategories')->name('scenario.category.toggle');
    Route::get('/scenario/category/clear', 'ScenariosController@clearCategories')->name('scenario.category.clear');

    //Loadlevel
    Route::get('/scenario/loadlevel/get', 'ScenariosController@getSelectedLoadLevel')->name('scenario.loadlevel.get');
    Route::get('/scenario/loadlevel/switch', 'ScenariosController@switchLoadLevel')->name('scenario.loadlevel.switch');

    //get current scenario in dashboard
    Route::get('/scenario/asset/get', 'ScenariosController@getScenarioAssets')->name('scenario.asset.get');
});

//Invites
Route::get('accept/{token}', 'InviteController@accept')->name('invites.accept');
Route::post('accept', 'InviteController@store')->name('invites.store');
