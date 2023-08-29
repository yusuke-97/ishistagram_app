<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FollowsController extends Controller
{
    public function store($userId)
    {
        auth()->user()->following->$userId->save();

        return back();
    }

    public function destroy($userId)
    {
        auth()->user()->following->$userId->delete();

        return back();
    }
}
