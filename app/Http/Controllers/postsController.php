<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Images;
use Illuminate\Http\Response;

class postsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['user', 'images', 'likes', 'comments', 'share'])->get();
        $sorted = collect($posts)->sortByDesc('id');
        $final  = [];
        foreach($sorted->values()->all() as $data){
            $final[] = $data;
        }
        $response = [
            'post'=> $final,
            'message'=> 'posts retrieved',
            'success' => true
        ];

        return response($response, 200);
        // return ;
    }

    public function myPosts()
    {
        $id= auth()->user()->id;

        $posts = Post::with(['user', 'images', 'likes', 'comments', 'share'])->where('user_id', $id)->get();
        $sorted = collect($posts)->sortByDesc('id');
        $final  = [];
        foreach($sorted->values()->all() as $data){
            $final[] = $data;
        }
        $response = [
            'post'=> $final,
            'message'=> 'posts retrieved',
            'success' => true
        ];

        return response($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $post = Post::create([
                'user_id'=> auth()->user()->id,
                'text'=> $request['text'],
            ]);
            
            $response = [
                'post_id'=> $post->id,
                'message'=> 'post created',
                'success' => true
            ];

            return response($response, 201);
    }



    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
