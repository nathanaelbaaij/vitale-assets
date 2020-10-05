<?php

namespace App\Http\Controllers;

use App\Invite;
use App\User;
use App\Avatar;
use Auth;
use App\Mail\InviteCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('permission:invite-list', ['only' => ['index']]);
        $this->middleware('permission:invite-create', ['only' => ['create', 'process']]);
        $this->middleware('permission:invite-delete', ['only' => ['delete', 'destroy']]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $invites = Invite::all();
        return view('invites.index', compact('invites'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('invites.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function process(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required|unique:users,name|min:3|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        do {
            //generate a random string using Laravel's str_random helper
            $token = str_random(64);
        } //check if the token already exists and if it does, try again
        while (Invite::where('token', $token)->first());

        //create a new invite record
        $invite = Invite::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'token' => $token
        ]);

        // send the email
        Mail::to($request->get('email'))->send(new InviteCreated($invite));

        // redirect back where we came from
        return redirect()->route('invites.index')->with('message', 'Uitnodiging is verstuurd naar ' . $request->get('email'));
    }

    /**
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function accept($token)
    {
        // Look up the invite
        if (!$invite = Invite::where('token', $token)->first()) {
            //if the invite doesn't exist show expired view
            return view('invites.expired');
        }
        //if everything is well, show the invite form
        return view('invites.accept', compact('invite'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //validate
        $this->validate(request(), [
            'name' => 'required|string|unique:users|min:3|max:255',
            'password' => 'required|confirmed|min:6|string'
        ]);

        //find invite
        if ($invite = Invite::where('token', $request->get('invite_token'))->first()) {
            // create the user with the details from the invite
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $invite->email;
            $user->password = bcrypt($request->get('password'));
            $user->save();

            //assign the role member
            $user->assignRole(['member']); //every new user is by default member

            //assign the default avatar
            $avatar = new Avatar();
            $avatar->image_url = "default.png";
            $avatar->active = true;
            $avatar->user_id = $user->id;
            $avatar->save();

            // delete the invite so it can't be used again
            if ($user) {
                $invite->delete();
            }

            //prepare auth array
            $credentials = [
                'email' => $invite->email,
                'password' => $request->get('password')
            ];

            //login the user and go to the dashboard, if else go to the login
            if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('auth.login');
            }
        }
        //cant find the token? show the expired view
        return view('invites.expired');
    }

    /**
     * @param Invite $invite
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Invite $invite)
    {
        $invite->delete();
        session()->flash('message', 'Uitnodiging van "' . $invite->name . '" is verwijderd.');
        return redirect()->route('invites.index');
    }
}
