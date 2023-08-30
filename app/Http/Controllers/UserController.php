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
        $currentUser = Auth::user(); // ログイン中のユーザーを取得

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
        $user = User::find($id);
        $following = $user->following;
        return response()->json($following);
    }
}
