<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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

        return back();
    }

}
