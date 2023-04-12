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
use App\Http\Controllers\StoriesController;
use App\Http\Controllers\StoriesMediaController;
use App\Http\Controllers\StoriesTextController;
use App\Http\Controllers\StoriesMediaViewController;
use App\Http\Controllers\StoriesTextViewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;

// StoriesMediaViewController

// 

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
    Route::PUT('/posts/update/{id}', [postsController::class, 'update']);
    Route::delete('/posts/delete/{id}', [postsController::class, 'destroy']);
    Route::get('/posts/allFriendsPost', [postsController::class, 'allFriendsPost']);
    
    Route::post('/posts/share', [shareController::class, 'store']);
    Route::get('/posts/share', [shareController::class, 'index']);
    Route::delete('/posts/share/{id}', [shareController::class, 'destroy']);
    Route::get('/posts/share/public', [shareController::class, 'publicShare']);
    Route::get('/posts/share/private', [shareController::class, 'privateShare']);

    // allFriendsPost
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
    Route::get('/friends/confirm', [FriendsController::class, 'confirm']);
    Route::delete('/removeFriend/{userid}', [FriendsController::class, 'destroy']);


    
    Route::get('/stories', [StoriesController::class, 'index']);
    Route::post('/stories/media', [StoriesMediaController::class, 'store']);
    Route::get('/stories/media/{id}', [StoriesMediaController::class, 'index']);
    Route::get('/stories/media/views/{id}', [StoriesMediaViewController::class, 'index']);
    Route::get('/stories/media/single/{id}', [StoriesMediaController::class, 'show']);
    Route::delete('/stories/media/{id}', [StoriesMediaController::class, 'destroy']);
    Route::post('/stories/text', [StoriesTextController::class, 'store']);
    Route::get('/stories/text/{id}', [StoriesTextController::class, 'index']);
    Route::delete('/stories/text/{id}', [StoriesTextController::class, 'destroy']);
    Route::get('/stories/text/views/{id}', [StoriesTextViewController::class, 'index']);
    Route::get('/stories/text/single/{id}', [StoriesTextController::class, 'show']);

    Route::post('/profile/pic', [ProfileController::class, 'pic']);
    Route::get('/profile/pic', [ProfileController::class, 'getProfilePic']);

    
    Route::post('/chat', [ChatController::class, 'store']);
    Route::get('/chat/{receiverid}', [ChatController::class, 'index']);

    
    Route::get('/users', [AuthController::class, 'Users']);




    Route::get('/notification', [NotificationController::class, 'index']);
    Route::delete('/notification/delete/{id}', [NotificationController::class, 'destroy']);
    Route::PUT('/notification/single/{id}', [NotificationController::class, 'update']);
    Route::PUT('/notification/markAll', [NotificationController::class, 'updateAll']);



    // NotificationController
    // 
    Route::post('/logout', [AuthController::class, 'Logout']);
});






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

