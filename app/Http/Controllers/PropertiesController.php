<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetProperty;
use DB;


class PropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $properties = DB::table('asset_properties')
            ->select('assets.id as assetId', 'assets.name as assetName', 'asset_properties.name as propertyName', 'value as propertyvalue')
            ->join('assets', 'assets.id', '=', 'asset_id')
            ->get();


        return $properties;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $assetid)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $properties = DB::table('asset_properties')
            ->select('assets.id as assetId', 'assets.name as assetName', 'asset_properties.name as propertyName', 'value as propertyvalue')
            ->join('assets', 'assets.id', '=', 'asset_id')
            ->where('asset_id', '=', $id)
            ->get();


        return $properties;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($assetId, $propId)
    {
        $property = DB::table('asset_properties')
            ->where('asset_id', '=', $assetId)
            ->where('name', '=', $propId);
        $property->delete();
    }
}
