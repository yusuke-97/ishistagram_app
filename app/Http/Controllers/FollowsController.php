<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowsController extends Controller
{
    public function store(User $user)
    {
        auth()->user()->following()->attach($user->id);

        return back();
    }

    public function destroy(User $user)
    {
        auth()->user()->following()->detach($user->id);

        return response()->json();
    }

    public function getFollowersList()
    {
        $user = Auth::user(); // ログイン中のユーザーを取得
        $followers = $user->followers; // フォロワーリストを取得

        $followersWithStatus = $followers->map(function ($follower) use ($user) {
            $followerArray = $follower->toArray();
            $followerArray['is_followed_by_current_user'] = $user->isFollowing($follower);
            return $followerArray;
        });

        return response()->json($followersWithStatus);
    }
}
