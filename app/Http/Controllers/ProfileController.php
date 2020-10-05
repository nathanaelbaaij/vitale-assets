<?php

namespace App\Http\Controllers;

use App\News;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $newsposts = News::where('user_id', $id)->paginate(5)->fragment('timeline');

        $avatarController = new AvatarController();
        $allAvatars = $avatarController->getAllAvatarsByUser();

        return view('profile.show', [
            'user' => $user,
            'newsposts' => $newsposts,
            'avatars_urls' => $allAvatars
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
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
        ]);

        $user = User::find($id);

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'phone' => request('phone'),
            'company' => request('company'),
            'city' => request('city'),
            'adres' => request('adres'),
            'house_number' => request('house_number'),
            'postal' => request('postal'),
        ]);

        return redirect()->route('profile.show', ['id' => $user->id, '#profile'])->with('message', 'Profiel is aangepast.');
    }
}
