<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
})->middleware('auth');

Auth::routes();
Route::get('/home',[HomeController::class,'index'])->name('home');


Route::group([
    'middleware' => 'auth',
    ],function (){
    Route::resource('/posts',PostController::class);
    Route::get('/user-posts',[PostController::class,'userPosts'])->middleware('auth');
     Route::get('posts/restore/{id}', [PostController::class, 'restore'])->name('posts.restore');
    Route::get('posts/restore-all', [PostController::class, 'restoreAll'])->name('posts.restoreAll');

}

);
Route::get('admin',[AdminController::class,'getPosts'])->middleware('admin');

// Route::get('posts/restore/{post}', [PostController::class, 'restore'])
//     ->name('posts.restore')
//     ->middleware('user.posts');

