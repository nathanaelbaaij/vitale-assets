<?php

namespace App\Http\Controllers;

use App\Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class AvatarController extends Controller
{
    /**
     * Returns the amount of images you have uploaded
     * to our database
     */
    private function getAvatarsUploadSize()
    {
        $userId = Auth::user()->id;
        $uploadAmount = Avatar::select('user_id')->where('user_id', $userId)->count();
        return $uploadAmount;
    }

    /**
     * Returns all the avatars uploaded by a user
     */
    public function getAllAvatarsByUser()
    {
        $userId = Auth::user()->id;
        $avatar = Avatar::select('image_url', 'id')->where('user_id', $userId)->get();
        return $avatar;
    }

    /**
     * When you upload a new image, the others go inactive
     */
    private function setAvatarsInactive()
    {
        $userId = Auth::user()->id;
        $avatars = Avatar::where('user_id', $userId)->where('active', 1)->get();

        foreach ($avatars as $avatar) {
            $avatarModel = Avatar::find($avatar->id);
            $avatarModel->update([
                'active' => false,
            ]);
        }
    }

    /**
     * Will update the profile picture to already
     * saved pictures
     */
    public function update($id)
    {
        $this->setAvatarsInactive();
        $avatar = Avatar::find($id);
        $avatar->active = 1;
        $avatar->save();
        session()->flash('message', 'Je avatar is gewijzigd');
        return redirect()->route('profile.show', ['id' => Auth::user()->id, '#profile']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //Validate
        $this->validate(request(), [
            'avatar' => 'required|bail|mimes:jpeg,jpg,png|max:4096',
        ]);

        //Setting all the other avatars to inactive
        $this->setAvatarsInactive();

        //Check if user has maximum of 3 avatars, if not delete the last one.
        if ($this->getAvatarsUploadSize() > 2) {
            session()->flash('warning', 'Je kunt maximaal 3 avatars uploaden, de oudste avatar is verwijderd');
            $this->deleteLatestAvatar();
        }

        //Making new avatar and saving in database
        $avatarFile = $request->file('avatar');
        $filename = time() . '.' . $avatarFile->getClientOriginalExtension();

        //save the image in /uploads/avatars/ folder
        Image::make($avatarFile)->resize(300, 300)->save(public_path('/uploads/avatars/' . $filename));

        //get the current loggedin user id
        $userId = Auth::user()->id;

        Avatar::create([
            'image_url' => $filename,
            'user_id' => $userId,
            'active' => true,
        ]);

        session()->flash('message', 'Nieuwe avatar is opgeslagen en in gebruik');
        return redirect()->route('profile.show', ['id' => $userId, '#profile']);
    }

    /**
     * Deleting the latest added avatar
     */
    private function deleteLatestAvatar()
    {
        $userId = Auth::user()->id;
        $userLatestAvatar = Avatar::where('user_id', $userId)->oldest()->first();
        if ($userLatestAvatar) {
            $imageUrl = $userLatestAvatar->image_url;
            if (!strpos($imageUrl, 'default')) {
                $locationImage = public_path('uploads/avatars/' . $imageUrl);
                unlink($locationImage);
            }
            $userLatestAvatar->delete();
        }
    }
}
