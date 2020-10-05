<?php

namespace App\Http\Controllers;

use App\Cascade;
use App\Asset;
use App\Consequence;
use Illuminate\Http\Request;

class CascadesController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:cascade-list');
        $this->middleware('permission:cascade-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:cascade-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:cascade-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cascades = Cascade::with('assetFrom')->with('assetTo')->with('consequence')->get();
        return view('cascades.index', compact('cascades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $assets = Asset::all();
        $consequences = Consequence::all();
        return view('cascades.create', compact('assets', 'consequences'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'asset-from' => 'required',
            'asset-to' => 'required|different:asset-from',
            'consequence' => 'required'
        ]);

        $consequence = request('consequence');
        $consequenceId = request('consequence');
        if (!is_numeric($consequence)) {
            //add new consequence to the database
            $lastAddedCon = Consequence::create([
                'description' => $consequence
            ]);
            $consequenceId = $lastAddedCon->id;
        }

        $cascade = Cascade::create([
            'asset_id_from' => request('asset-from'),
            'asset_id_to' => request('asset-to'),
            'consequence_id' => $consequenceId,
        ]);

        $assetFrom = Asset::find($cascade->asset_id_from);
        $assetTo = Asset::find($cascade->asset_id_to);

        session()->flash('message', 'Cascade "' . $assetFrom->id . ' ' . $assetFrom->name . ', ' . $assetTo->id . ' ' . $assetTo->name . '" is aangemaakt.');
        return redirect()->route('cascades.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cascade $cascade
     * @return \Illuminate\Http\Response
     */
    public function show(Cascade $cascade)
    {
        return view("cascades.show", compact('cascade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cascade $cascade
     * @return \Illuminate\Http\Response
     */
    public function edit(Cascade $cascade)
    {
        $assets = Asset::all();
        $consequences = Consequence::all();
        return view('cascades.edit', compact('cascade', 'assets', 'consequences'));
    }

    /**
     * @param Request $request
     * @param Cascade $cascade
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Cascade $cascade)
    {
        $this->validate(request(), [
            'asset-from' => 'required',
            'asset-to' => 'required|different:asset-from',
            'consequence' => 'required'
        ]);

        $consequence = request('consequence');
        $consequenceId = request('consequence');
        if (!is_numeric($consequence)) {
            //add new consequence to the database
            $lastAddedCon = Consequence::create([
                'description' => $consequence
            ]);
            $consequenceId = $lastAddedCon->id;
        }

        $cascade->update([
            'asset_id_from' => request('asset-from'),
            'asset_id_to' => request('asset-to'),
            'consequence_id' => $consequenceId,
        ]);

        $assetFrom = Asset::find(request('asset-from'));
        $assetTo = Asset::find(request('asset-to'));

        session()->flash('message', 'Cascade "' . $assetFrom->id . ' ' . $assetFrom->name . ', ' . $assetTo->id . ' ' . $assetTo->name . '" is gewijzigd.');
        return redirect()->route('cascades.index');
    }

    /**
     * @param Cascade $cascade
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Cascade $cascade)
    {
        $cascade->delete();
        session()->flash('message', 'Cascade "' . $cascade->id . '" is verwijderd.');
        return redirect()->route('cascades.index');
    }
}
