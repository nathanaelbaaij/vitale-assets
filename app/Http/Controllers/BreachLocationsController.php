<?php

namespace App\Http\Controllers;

use App\BreachLocation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class BreachLocationsController
 * @package App\Http\Controllers
 * @author Ronnie
 */
class BreachLocationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:breach-list');
        $this->middleware('permission:breach-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:breach-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:breach-delete', ['only' => ['delete', 'destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breaches = BreachLocation::orderBy('id', 'asc')->get();
        return view("breaches.index", compact("breaches"));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("breaches.create");
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|min:3|max:255|unique:breach_locations',
            'name' => 'required|min:3|max:255',
            'longname' => 'required|min:3|max:255',
            'xcoord' => 'required|numeric',
            'ycoord' => 'required|numeric',
            'dykering' => 'required|integer',
            'vnk2' => 'required|integer',
        ]);

        $breach = BreachLocation::create($request->all());
        session()->flash('message', 'Breslocatie "' . $breach->name . '" is aangemaakt.');
        return redirect('/breaches');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\BreachLocation $breach
     * @return \Illuminate\Http\Response
     */
    public function show(BreachLocation $breach)
    {
        return view("breaches.show", compact('breach'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\BreachLocation $breach
     * @return \Illuminate\Http\Response
     */
    public function edit(BreachLocation $breach)
    {
        return view("breaches.edit", compact('breach'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \App\BreachLocation $breach
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BreachLocation $breach)
    {
        $request->validate([
            'code' => [
                'required',
                Rule::unique('breach_locations')->ignore($breach->id),
                'min:3',
                'max:255',
            ],
            'name' => 'required|min:3|max:255',
            'longname' => 'required|min:3|max:255',
            'x_coordinate' => 'required|numeric',
            'y_coordinate' => 'required|numeric',
            'dykering' => 'required|integer',
            'vnk2' => 'required|integer',
        ]);

        $breach->update($request->all());

        session()->flash('message', 'Breslocatie "' . $breach->name . '" is gewijzigd.');
        return redirect('/breaches');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\BreachLocation $breach
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(BreachLocation $breach)
    {
        $breach->delete();
        session()->flash('message', 'Breslocatie "' . $breach->name . '" is verwijderd.');
        return redirect()->route('breaches.index');
    }
}
