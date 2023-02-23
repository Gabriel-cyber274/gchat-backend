<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\postsController;
use App\Http\Controllers\mediaController;
use App\Http\Controllers\likeController;
use App\Http\Controllers\commentController;
use App\Http\Controllers\shareController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/index', function () {
    return 'johnny drill';
});

Route::post('/register', [AuthController::class, 'Register']);
Route::post('/login', [AuthController::class, 'Login']);


Route::group(['middleware'=> ['auth:sanctum']], function () {
    Route::get('/posts', [postsController::class, 'index']);
    Route::post('/posts', [postsController::class, 'store']);
    Route::post('/posts/share', [shareController::class, 'store']);
    Route::get('/posts/share', [shareController::class, 'index']);
    Route::get('/myPosts', [postsController::class, 'myPosts']);

    Route::post('/media', [mediaController::class, 'store']);
    Route::get('/media', [mediaController::class, 'index']);

    Route::post('/like', [likeController::class, 'store']);
    Route::delete('/unlike/{id}', [likeController::class, 'destroy']);

    Route::post('/comment', [commentController::class, 'store']);
    Route::post('/post-comments', [commentController::class, 'index']);
    
    Route::post('/logout', [AuthController::class, 'Logout']);
});






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
