<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Response;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    public function Search ($search) {
        $posts = Post::with(['user', 'likes', 'comments', 'share'])->where('text', 'like', '%'.$search.'%')->get();
        $user = User::with('profilePic')->where('name', 'like', '%'.$search.'%')->get();

        $users = $user->filter(function($value, $key){
            return $value->id !== auth()->user()->id;
        });

        
        $response = [
            'post'=>$posts,
            'user'=>$users,
            'message'=> 'Search retrieved',
            'success' => true
        ];

        return response($response);
    }
}
