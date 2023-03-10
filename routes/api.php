<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\postsController;
use App\Http\Controllers\mediaController;
use App\Http\Controllers\likeController;
use App\Http\Controllers\commentController;
use App\Http\Controllers\shareController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\SubCommentController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\saveController;


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
    Route::get('/posts/private', [postsController::class, 'private']);
    Route::post('/posts', [postsController::class, 'store']);
    Route::post('/posts/share', [shareController::class, 'store']);
    Route::get('/posts/share', [shareController::class, 'index']);
    Route::delete('/posts/share/{id}', [shareController::class, 'destroy']);
    Route::get('/posts/share/public', [shareController::class, 'publicShare']);
    Route::get('/posts/share/private', [shareController::class, 'privateShare']);
    Route::get('/myPosts', [postsController::class, 'myPosts']);
    Route::post('/posts/save', [saveController::class, 'store']);
    Route::get('/posts/save', [saveController::class, 'index']);
    Route::delete('/posts/save/{id}', [saveController::class, 'destroy']);

    Route::post('/media', [mediaController::class, 'store']);
    Route::get('/media', [mediaController::class, 'index']);

    Route::post('/like', [likeController::class, 'store']);
    Route::get('/like/{id}', [likeController::class, 'index']);
    Route::delete('/unlike/{id}', [likeController::class, 'destroy']);

    Route::post('/comment', [commentController::class, 'store']);
    Route::post('/subComment', [SubCommentController::class, 'store']);
    Route::get('/subComment/{id}', [SubCommentController::class, 'index']);
    Route::post('/commentLike', [CommentLikeController::class, 'store']);
    Route::get('/commentLike/{id}', [CommentLikeController::class, 'index']);
    Route::delete('/commentUnlike/{id}', [CommentLikeController::class, 'destroy']);
    Route::delete('/deleteComment/{id}', [commentController::class, 'destroy']);
    Route::get('/post-comments/{id}', [commentController::class, 'index']);

    
    Route::post('/friends', [FriendsController::class, 'store']);
    Route::get('/friends', [FriendsController::class, 'index']);
    Route::delete('/removeFriend/{id}/{id2}', [FriendsController::class, 'destroy']);
    
    Route::post('/logout', [AuthController::class, 'Logout']);
});






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

