<?php

namespace App\Http\Controllers;

use App\Logbook;
use App\LogbookReverted;
use Illuminate\Http\Request;
use App\Asset;
use Carbon\Carbon;

class LogbookController extends Controller
{

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id)
    {
        $log = Logbook::where('asset_id', $id)->first();
        $log->delete();

        session()->flash('message', 'De wijziging is succesvol permanent verwijderd');
        return redirect()->route('logbook.index');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revert($id)
    {
        $log = Logbook::where('asset_id', $id)->first();
        $asset = Asset::find($id)->first();

        $reverted_asset = json_decode($log->json_data);
        $asset->name = $reverted_asset->name;
        $asset->description = $reverted_asset->description;
        $asset->category_id = $reverted_asset->category_id;
        $asset->x_coordinate = $reverted_asset->x_coordinate;
        $asset->y_coordinate = $reverted_asset->y_coordinate;
        $asset->threshold_correction = $reverted_asset->threshold_correction;
        $asset->save();

        $saved = new LogbookReverted;
        $saved->asset_id = $id;
        $saved->save();

        $log->delete();

        session()->flash('message', 'De asset is nu succesvol teruggezet naar de datum: ' . $reverted_asset->updated_at);
        return redirect()->route('logbook.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $log = Logbook::select(
            'logbooks.id as lb_id',
            'logbooks.asset_id as lb_ass_id',
            'us.name as username',
            'av.image_url',
            'logbooks.json_data as lb_json_data',
            'as.name',
            'as.description',
            'as.category_id',
            'as.x_coordinate',
            'as.y_coordinate',
            'as.threshold_correction'
        )
            ->where('logbooks.updated_at', '>=', Carbon::now()->subDay(7))
            ->join('assets as as', 'as.id', '=', 'logbooks.asset_id')
            ->where('av.active', '=', '1')
            ->join('users as us', 'us.id', '=', 'logbooks.user_id')
            ->join('avatars as av', 'av.user_id', '=', 'logbooks.user_id')
            ->orderBy('logbooks.updated_at', 'DESC')
            ->get();

        $reverteds = LogbookReverted
                ::join('assets as as', 'as.id', '=', 'logbook_reverteds.asset_id')
                ->limit(20)
                ->get();

        return view('logbook.show')->with('logs', $log)->with('reverteds', $reverteds);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Logbook  $logbook
     * @return \Illuminate\Http\Response
     */
    public function show(Logbook $logbook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Logbook  $logbook
     * @return \Illuminate\Http\Response
     */
    public function edit(Logbook $logbook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Logbook  $logbook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Logbook $logbook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Logbook  $logbook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logbook $logbook)
    {
        //
    }
}
