<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//cate functionalittu
/*
Route::get('/admin-page', function () {
  /*  if(Gate::allows('visitAdminPages')){
        return 'is admin';
    }
    return 'not admin';*
    return 'is admin';

})->middleware('can:visitAdminPages');
*/

//user routs
Route::get('/', [UserController::class,'homepage'])->name('login');
Route::post('/register', [UserController::class,'register'])->middleware('guest');;
Route::post('/login', [UserController::class,'login'])->middleware('guest');;
Route::post('/logout', [UserController::class,'logout'])->middleware('MustBeLoggedIn');
Route::get('/manage-avatar', [UserController::class,'showAvatarForm'])->middleware('MustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class,'storAvatar'])->middleware('MustBeLoggedIn');

Route::post('/create-follow/{user:username}', [FollowController::class,'createFollow'])->middleware('MustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class,'removeFollow'])->middleware('MustBeLoggedIn');


//post routes

Route::get('/create-post', [PostController::class,'_form'])->middleware('MustBeLoggedIn');
Route::post('/create-post', [PostController::class,'create'])->middleware('auth');
Route::get('/posts/{post}', [PostController::class,'singlePost']);
Route::delete('/posts/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/posts/{post}/edit', [PostController::class, 'show_edit_form'])->middleware('can:update,post');
Route::put('/posts/{post}', [PostController::class, 'update_post'])->middleware('can:update,post');


//profile

Route::get('/profile/{user:username}', [UserController::class,'profile']);
Route::get('/profile/{user:username}/followers', [UserController::class,'followers']);
Route::get('/profile/{user:username}/following', [UserController::class,'following']);



