<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowsController extends Controller
{
    /**
     * 認証済みのユーザーが指定されたユーザーをフォロー
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(User $user)
    {
        auth()->user()->following()->attach($user->id);

        return response()->json();
    }

    /**
     * 認証済みのユーザーが指定されたユーザーのフォローを解除
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        auth()->user()->following()->detach($user->id);

        return response()->json();
    }

    /**
     * 認証済みのユーザーが指定されたユーザーをフォロー
     * 成功後、前のページにリダイレクト
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow(User $user)
    {
        auth()->user()->following()->attach($user->id);

        return back();
    }

    /**
     * 認証済みのユーザーが指定されたユーザーのフォローを解除
     * 成功後、前のページにリダイレクト
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user->id);

        return back();
    }
}
