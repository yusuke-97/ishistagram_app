<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FollowsController extends Controller
{
    public function store(User $user)
    {
        Auth::user()->following()->attach($user->id);

        return back();
    }

    public function destroy(User $user)
    {
        Auth::user()->following()->detach($user->id);

        return back();
    }
}
