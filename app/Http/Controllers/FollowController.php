<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    //
    public function createFollow(User $user)
    {
        if (auth()->user()->id == $user->id) {
            return back()->with('danger', 'You cannot follow yourself');
        }

        $exist = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();

        if($exist){
            return back()->with('danger', 'You have already followed this user');
        }

        $model = new Follow();
        $model->user_id = auth()->user()->id;
        $model->followeduser = $user->id;
        $model->save();

        return back()->with('success', 'you have successfully followed ' . $user->username);
    }


    public function removeFollow(User $user)
    {
        Follow::where([['user_id','=',auth()->user()->id],['followeduser','=',$user->id]])->delete();
        return back()->with('success', 'You have unfollowed ' . $user->username);
    }
}
