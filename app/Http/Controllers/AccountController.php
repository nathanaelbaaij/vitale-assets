<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Validator;
use Request;

class AccountController extends Controller
{
    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $user = Auth::user();

        $validation = Validator::make(Request::all(), [
            'old_password' => 'hash:' . $user->password,
            'new_password' => 'required|different:old_password|confirmed'
        ]);

        if ($validation->fails()) {
            return redirect()->route('profile.show', ['id' => $user->id, '#account'])->withErrors($validation->errors());
        }

        $user->password = Hash::make(request('new_password'));
        $user->save();

        return redirect()->route('profile.show', ['id' => $user->id, '#account'])->with('message', 'Wachtwoord is gewijzigd.');
    }
}
