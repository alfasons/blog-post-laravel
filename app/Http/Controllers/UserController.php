<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
            return view('home-page-feed');
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
        $posts = $user->posts()->get();

        return view('users/profile', ['username' => $user->username, 'posts' => $posts, 'total_post_count' => $user->posts()->count()]);
    }
}
