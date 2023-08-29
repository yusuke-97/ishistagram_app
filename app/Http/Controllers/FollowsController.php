<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowsController extends Controller
{
    public function store($id)
    {
        auth()->user()->following()->attach($id);

        return back();
    }

    public function destroy($id)
    {
        auth()->user()->following()->detach($id);

        return back();
    }
}
