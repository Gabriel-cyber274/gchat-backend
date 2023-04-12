<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Likes;
use App\Models\Post;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class likeController extends Controller
{
    
    public function index($id)
    {
        
        $likes = Likes::with(['user'])->where("post_id", $id)
        ->get();

        $response = [
            'likes'=> $likes,
            'message'=> 'post likes retrieved successful',
            'success' => true
        ];

        return response($response);
    }

    public function store(Request $request)
    {
        //
        
        $id= Auth()->user()->id;
        
        $fields = $request->validate([
            'post_id'=> 'required|integer',
        ]);
        
        $like = Likes::create([
            'user_id'=> $id,
            'post_id'=> $request->post_id
        ]);
        
        

        
        $posts = Post::where('id', $request->post_id)->get();
        $like->posts()->attach($posts);

        

        if($posts->first()->user_id !== Auth()->user()->id && !is_null($posts->first()->text)) {
            $user = User::with('profilePic')->where('id', $posts->first()->user_id)->get();
            $user_not = User::with('profilePic')->where('id', auth()->user()->id)->get()->first();
    
            $notification = Notification::create([
                'image'=> $user_not->profilePic,
                'user_id'=> $posts->first()->user_id,
                'name'=> $user_not->name,
                'message'=> 'liked your post'. ' '.'('.Str::limit($posts->first()->text, 20, '...').')',
                'page_id'=> $posts->first()->id,
                'read'=> false
            ]);
            $notification->user()->attach($user);
        }
        else if ($posts->first()->user_id !== Auth()->user()->id && is_null($posts->first()->text)) {
            $user = User::with('profilePic')->where('id', $posts->first()->user_id)->get();
            $user_not = User::with('profilePic')->where('id', auth()->user()->id)->get()->first();
    
            $notification = Notification::create([
                'image'=> $user_not->profilePic,
                'user_id'=> $posts->first()->user_id,
                'name'=> $user_not->name,
                'message'=> 'liked your post',
                'page_id'=> $posts->first()->id,
                'read'=> false
            ]);
            $notification->user()->attach($user);

        }

        $response = [
            'message'=> 'post liked',
            'success' => true
        ];

        return response($response, 201);
    }
    
    public function destroy($id)
    {
        //
        $like = Likes::destroy($id);
        
        if($like === 1) {
            $response = [
                'message'=> 'post unliked',
                'success' => true
            ];
            return response($response);
        }
        else {
            $response = [
                'message'=> 'error',
                'success' => false
            ];
            return response($response);
        }

    }
}
