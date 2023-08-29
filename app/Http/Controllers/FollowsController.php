<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowsController extends Controller
{
    public function store(User $user)
    {
        auth()->user()->following()->attach($user->id);

        return response()->json(['message' => 'destroy method in FollowsController was called.', 'targetUserId' => $user->id]);
    }

    public function destroy(User $user)
    {
        auth()->user()->following()->detach($user->id);

        return back();
    }
}
