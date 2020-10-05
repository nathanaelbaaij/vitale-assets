<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Asset;
use App\Http\Resources\AssetResource;
use App\Http\Resources\AssetsResource;

class AssetsAPIController extends Controller
{
    /**
     * @param Request $request
     * @return AssetsResource
     */
    public function index(Request $request)
    {
        //remove data wrap
        AssetsResource::withoutWrapping();

        //Get all assets and filter them if needed / manipulate the asset api results
        if ($request->all()) {
            $categoryIdArray = [];
            //get all getters from the url and put them into an array
            foreach ($request->all() as $key => $value) {
                $categoryIdArray[] = intval($value);
            }

            //check if parent category has child categories, if so put the id's into the categoryIdArray
            foreach ($categoryIdArray as $catId) {
                if ($categories = Category::select('id', 'parent_id')->where('parent_id', $catId)->get()) {
                    foreach ($categories as $subCategory) {
                        $categoryIdArray[] = $subCategory->id;
                    }
                }
            }

            //get getters from url and put the in the where query
            $assets = Asset::whereIn('category_id', $categoryIdArray)->get();
        } else {
            //get all assets and send them to AssetResource
            $assets = Asset::all();
        }
        return new AssetsResource($assets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param Asset $asset
     * @return AssetResource
     */
    public function show(Asset $asset)
    {
        //remove data wrap
        AssetsResource::withoutWrapping();
        return new AssetResource($asset);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
