<?php

use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});


Route::prefix('profile')->group(function () {
    Route::resource('posts', PostController::class)->middleware(['auth', 'verified']);
});

Route::get('/profile/index', [ProfileController::class, 'index'])->name('profile.index')->middleware(['auth', 'verified']);

// ラベルによるフィルタリングをサポートする既存のルート
Route::get('profile/{profile}/show/{label?}', [ProfileController::class, 'showLabel'])->name('profile.show.withLabel')->middleware(['auth', 'verified']);

// 他の `profile` に関するルーティング
Route::resource('profile', ProfileController::class)->parameters([
    'profile' => 'profile'
])->middleware(['auth', 'verified']);
Route::get('profile', [ProfileController::class, 'show'])->name('profile.default')->middleware(['auth', 'verified']);



Route::resource('/likes', LikeController::class)->middleware(['auth', 'verified']);
Route::post('follow/{user}', [FollowsController::class, 'store'])->name('follow');
Route::delete('unfollow/{user}', [FollowsController::class, 'destroy'])->name('unfollow');
Route::post('follow/{user}/followers', [FollowsController::class, 'follow'])->name('follow.profile');
Route::delete('unfollow/{user}/followers', [FollowsController::class, 'unfollow'])->name('unfollow.profile');


// Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::resource('/users', UserController::class)->middleware(['auth', 'verified']);

Route::get('/user/{id}/followers', [UserController::class, 'getFollowers']);
Route::get('/user/{id}/following', [UserController::class, 'getFollowing']);

Route::resource('/labels', LabelController::class)->middleware(['auth', 'verified']);

Route::get('/search/tags', [SearchController::class, 'searchTags'])->name('tags.search');
Route::get('/autocomplete/tags', [SearchController::class, 'autocompleteTags'])->middleware('auth');
Route::get('/autocomplete/users', [SearchController::class, 'autocompleteUsers'])->middleware('auth');

Route::resource('comments', CommentController::class)->middleware(['auth', 'verified']);

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
