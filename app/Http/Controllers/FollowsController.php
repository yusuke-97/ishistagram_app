<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FollowsController extends Controller
{
    public function store($id)
    {
        auth()->user()->following()->attach($id);

        return back();
    }

    public function destroy(User $user)
    {
        Log::info('Entered destroy method with user:', ['user' => $user]);

        auth()->user()->following()->detach($user);

        return back();
    }
}
