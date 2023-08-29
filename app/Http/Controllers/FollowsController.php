<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowsController extends Controller
{
    public function store($userId)
    {
        auth()->user()->follow($userId);

        return back();
    }

    public function destroy($userId)
    {
        auth()->user()->unfollow($userId);

        return back();
    }
}
