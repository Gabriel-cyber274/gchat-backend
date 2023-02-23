<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Share;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Response;

class shareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $id= auth()->user()->id;

        $share = Share::with(['post'])->where('user_id', $id)->get();

        $try=[];
        foreach($share as $sha) {
            $try[]=$sha->post->id;
        }
        
        $posts = Post::with(['user', 'images'])->find($try);
        $sorted = collect($posts)->sortByDesc('id');
        $final  = [];
        foreach($sorted->values()->all() as $data){
            $final[] = $data;
        }

        $response = [
            'post'=> $final,
            'message'=> 'shared post retrieved',
            'success' => true
        ];

        return response($response);

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
        //
        $this->validate($request, [
            'post_id'=> 'required|integer',
            // 'user_id'=> 'required|integer'
        ]);

        $share = Share::create([
            'user_id'=> auth()->user()->id,
            'post_id'=> $request['post_id'],
        ]);

        
        $user = User::where('id', auth()->user()->id)->get();
        $share->user()->attach($user);
        
        
        $response = [
            'share'=> $share,
            'message'=> 'post shared',
            'success' => true
        ];

        return response($response);

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
