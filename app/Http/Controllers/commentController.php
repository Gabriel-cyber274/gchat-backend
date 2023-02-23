<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comments;
use App\Models\Post;
use Illuminate\Http\Response;

class commentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        
        $fields = $request->validate([
            'post_id'=> 'required|integer',
        ]);
        

        $comments = Comments::with(['user'])->where("post_id", $request['post_id'])
        ->get();

        $response = [
            'comments'=> $comments,
            'message'=> 'comment successful',
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
            'comment'=> 'required|string',
        ]);
        
        $comments = Comments::create([
            'comment'=> $request->comment,
            'post_id'=> $request['post_id'],
            'user_id'=> $id
        ]);
        
        $posts = Post::where('id', $request->post_id)->get();
        $comments->posts()->attach($posts);

        $response = [
            'message'=> 'comment successful',
            'success' => true
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
