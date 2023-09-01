<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('users.index', ['users' => $users]);
    }

    public function getFollowers($id)
    {
        $currentUser = Auth::user();

        $user = User::find($id);
        $followers = $user->followers;

        $followersWithStatus = $followers->map(function ($follower) use ($currentUser) {
            $followerArray = $follower->toArray();
            $followerArray['is_followed_by_current_user'] = $currentUser->isFollowing($follower);
            return $followerArray;
        });

        return response()->json($followersWithStatus);
    }

    public function getFollowing($id)
    {
        $currentUser = Auth::user();

        $user = User::find($id);
        $following = $user->following;

        $followingWithStatus = $following->map(function ($follow) use ($currentUser) {
            $followArray = $follow->toArray();
            $followArray['is_followed_by_current_user'] = $currentUser->isFollowing($follow);
            return $followArray;
        });

        return response()->json($followingWithStatus);
    }
}
