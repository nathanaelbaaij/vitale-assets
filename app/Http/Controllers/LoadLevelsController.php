<?php

namespace App\Http\Controllers;

use App\LoadLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class LoadLevelsController
 * @package App\Http\Controllers
 * @author Nathanael Baaij
 */
class LoadLevelsController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:loadlevel-list');
        $this->middleware('permission:loadlevel-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:loadlevel-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:loadlevel-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     *
     */
    public function index()
    {
        $loadLevels = LoadLevel::all();
        return view('loadlevels.index', compact('loadLevels'));
    }

    /**
     *
     */
    public function create()
    {
        return view('loadlevels.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|min:3|max:255|unique:load_levels',
            'name' => 'required|min:3|max:255|unique:load_levels',
            'description' => 'max:1024',
        ]);

        LoadLevel::create([
            'code' => request('code'),
            'name' => request('name'),
            'description' => request('description'),
        ]);

        session()->flash('message', 'Belastingniveau "' . request('name') . '" is aangemaakt.');
        return redirect()->route('loadlevels.index');
    }

    /**
     * @param LoadLevel $loadlevel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(LoadLevel $loadlevel)
    {
        return view("loadlevels.show", compact('loadlevel'));
    }

    /**
     * @param LoadLevel $loadlevel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(LoadLevel $loadlevel)
    {
        return view('loadlevels.edit', compact('loadlevel'));
    }

    /**
     * @param Request $request
     * @param LoadLevel $loadlevel
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, LoadLevel $loadlevel)
    {
        $this->validate($request, [
            'code' => [
                Rule::unique('load_levels')->ignore($loadlevel->id),
                'required',
                'min:3',
                'max:255',
            ],
            'name' => [
                Rule::unique('load_levels')->ignore($loadlevel->id),
                'required',
                'min:3',
                'max:255',
            ],
            'description' => 'max:1024',
        ]);

        $loadlevel->update([
            'code' => request('code'),
            'name' => request('name'),
            'description' => request('description'),
        ]);

        session()->flash('message', 'Belastingniveau "' . request('name') . '" is gewijzigd.');
        return redirect()->route('loadlevels.index');
    }

    /**
     * @param LoadLevel $loadlevel
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(LoadLevel $loadlevel)
    {
        $loadlevel->delete();
        session()->flash('message', 'Belastingniveau "' . $loadlevel->name . '" is verwijderd.');
        return redirect()->route('loadlevels.index');
    }
}
