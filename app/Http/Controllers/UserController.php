<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{

    public function storAvatar(Request $request)
    {
      $request->validate([
            'avatar' => 'required|image|max:2000',
        ]);

        $user = auth()->user();
        $fileName = $user->id . '-' . uniqid() . '.jpg';

        $manager = new ImageManager(new Driver);
        $image = $manager->read($request->file('avatar'));
        $imgData = $image->cover(120, 120)->toJpeg();
        Storage::put("public/avatars/".$fileName, $imgData);

        $old_avatar = $user->avartar;
        $user->avartar = $fileName;
        $user->save();
        if ($old_avatar !== '/fallback-avatar.jpg') {
            Storage::delete(str_replace("/storage/", "/public/", $old_avatar));
        }
        return back()->with('success', 'Congrats on new avatar');
    }
    public function showAvatarForm()
    {
        return view('users.avatar-form');
    }
    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out.');
    }

    public function homepage()
    {
        if (auth()->check()) {
            return view('home-page-feed',['posts'=>auth()->user()->feedsPosts()->latest()->get()]);
        } else {
            return view('home-page');
        }
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'You have successfully logged in.');
        } else {
            return redirect('/')->with('danger', 'Invalid login.');
        }
    }

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);

        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success', 'Thank you for creating an account.');
    }

    public function profile(User $user)
    {
        $this->getSharedData($user);
        return view('users/profile', ['posts' => $user->posts()->latest()->get()]);


    }

    public function followers(User $user)
    {
        $this->getSharedData($user);
        return view('users/followers', ['followers' => $user->followers()->latest()->get()]);


    }
    public function following(User $user)
    {
        $this->getSharedData($user);
        return view('users/following', ['following' => $user->followingTheseUsers()->latest()->get()]);


    }
    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avartar' => $user->avartar, 'username' => $user->username, 'postCount' => $user->posts()->count(),'followingCount'=>$user->followingTheseUsers->count(),'followersCount'=>$user->followers->count()]);
    }
}
