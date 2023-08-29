<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('users.index', ['users' => $users]);
    }

    public function getFollowers($id)
    {
        $user = User::find($id);
        $followers = $user->followers;
        return response()->json($followers);
    }

    public function getFollowing($id)
    {
        $user = User::find($id);
        $following = $user->following;
        return response()->json($following);
    }
}
