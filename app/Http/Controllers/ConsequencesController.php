<?php

namespace App\Http\Controllers;

use App\Cascade;
use App\Consequence;
use Illuminate\Http\Request;

class ConsequencesController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:consequence-list');
        $this->middleware('permission:consequence-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:consequence-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:consequence-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $consequences = Consequence::all();
        return view('consequences.index', compact('consequences'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('consequences.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'description' => 'required|min:3|max:1024',
        ]);

        Consequence::create([
            'description' => request('description'),
        ]);

        session()->flash('message', 'Consequentie "' . request('description') . '" is aangemaakt.');
        return redirect('/consequences');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Consequence $consequence
     * @return \Illuminate\Http\Response
     */
    public function show($consequence)
    {
        $consequence = Consequence::with('cascades')->find($consequence);
        return view("consequences.show", compact('consequence'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Consequence $consequence
     * @return \Illuminate\Http\Response
     */
    public function edit(Consequence $consequence)
    {
        return view('consequences.edit', compact('consequence'));
    }

    /**
     * @param Request $request
     * @param Consequence $consequence
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Consequence $consequence)
    {
        $this->validate(request(), [
            'description' => 'required|min:3|max:1024',
        ]);

        $consequence->update([
            'description' => request('description'),
        ]);

        session()->flash('message', 'Consequentie "' . request('description') . '" is gewijzigd.');
        return redirect('/consequences');
    }

    /**
     * @param Consequence $consequence
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Consequence $consequence)
    {
        $consequenceDescription = $consequence->description;
        $cascades = Cascade::where('consequence_id', $consequence->id)->get();

        //Count the results, 1 of more is true 0 is false
        if ($amountOfCascades = $cascades->count()) {
            return redirect()->route('consequences.delete', ['id' => $consequence->id])
                ->with('errors', 'Consequentie komt nog voor in ' . $amountOfCascades . ' cascade(s).');
        }

        $consequence->delete();
        session()->flash('message', 'Consequentie "' . $consequenceDescription . '" is verwijderd.');
        return redirect('/consequences');
    }
}
