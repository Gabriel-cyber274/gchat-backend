<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Likes;
use App\Models\Post;
use Illuminate\Http\Response;

class likeController extends Controller
{
    public function store(Request $request)
    {
        //
        
        $id= auth()->user()->id;
        
        $fields = $request->validate([
            'post_id'=> 'required|integer',
            'like'=> 'required|integer'
        ]);
        
        $like = Likes::create([
            'like'=> $request->like,
        ]);
        

        
        $posts = Post::where('id', $request->post_id)->get();
        $like->posts()->attach($posts);

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
