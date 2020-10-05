<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Class RolesController
 * @package App\Http\Controllers
 * @author Rachelle de Zwart
 */
class RolesController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:role-list');
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     * Get all the roles to the roles.index page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * show the relevant role by id
     * return a show view with the given data
     * @param $id role id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $role = Role::find($id);
        return view('roles.show', compact('role'));
    }

    /**
     * return the roles create view with all the roles from the database
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => [
                'required',
                'unique:roles,name',
                'min:3',
                'max:255',
            ],
        ]);

        //create new role
        $role = Role::create(['name' => request('name')]);

        $permissions = request()->get('permissions', []);
        $role->syncPermissions($permissions);

        //back to index
        return redirect()->route('roles.index')->with('message', 'Rol met de naam "' . request('name') . '" is aangemaakt.');
    }

    /**
     * Find the relevant role by id with all the roles in the database
     * return a edit view with the data
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //get role by id and get all permissions
        $role = Role::find($id);
        $permissions = Permission::all();

        //check if role is administrator
        $isAdmin = false;
        if ($role->name === 'administrator') {
            $isAdmin = true;
        }

        return view('roles.edit', compact('role', 'permissions', 'isAdmin'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'administrator') {
            $role->syncPermissions(Permission::all());
            return redirect()->route('roles.index')->with('message', 'De Rol "administrator" heeft altijd alle permissies.');
        }

        $permissions = request()->get('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('message', 'Rol "' . $role->name . '" is gewijzigd.');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        //find role
        $role = Role::findOrFail($id);
        //set name
        $roleName = $role->name;
        //check if role is administrator, administrator role cant be removed from the system
        if ($role->name === 'administrator') {
            return redirect()->route('roles.index')->with('message', 'De rol administrator mag niet verwijdert worden.');
        }
        //remove all permissions from role
        $role->syncPermissions([]);
        //delete role
        $role->delete();
        //redirect to index
        return redirect()->route('roles.index')->with('message', 'Rol "' . $roleName . '" is verwijderd.');
    }
}
