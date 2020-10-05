<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use App\User;
use App\Avatar;
use App\Mail\Registered;
use Hash;

use Spatie\Permission\Models\Role;

/**
 * Class UsersController
 * @package App\Http\Controllers
 * @author Rachelle de Zwart
 */
class UsersController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:user-list');
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     * Get all the users to the users.index page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * show the relevant user by id
     * return a show view with the given data
     * @param $id user id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     *
     */
    public function create()
    {
    }

    /**
     *
     */
    public function store()
    {
    }

    /**
     * Find the relevant user by id with all the users in the database
     * return a edit view with the data
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name' => [
                'required',
                Rule::unique('users')->ignore($id),
                'min:3',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'role' => 'required',
            'password' => 'required'
        ]);

        $user = User::find($id);

        $new_password = request('password');
        if (Hash::needsRehash($new_password)) {
            $new_password = Hash::make(request('password'));
        }

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'phone' => request('phone'),
            'company' => request('company'),
            'city' => request('city'),
            'adres' => request('adres'),
            'house_number' => request('house_number'),
            'postal' => request('postal'),
            'password' => $new_password,
        ]);

        //update roles
        $user->syncRoles(request('role'));

        return redirect()->route('users.index')->with('message', 'Gebruiker met de naam "' . request('name') . '" is gewijzigd.');
    }

    /**
     * Checks if user exists if so it gets deleted and
     * the user will be redirected back to the user.index
     * with a success flash message
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($userRoles = $user->roles->first()) {
            $user->removeRole($userRoles);
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('message', 'Gebruiker met de naam "' . $userName . '" is verwijderd.');
    }
}
