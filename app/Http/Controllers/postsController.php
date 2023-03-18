<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Friends;
use App\Models\Images;
use Illuminate\Http\Response;
use App\Events\PostCreated;
use Illuminate\Support\Facades\Auth;

class postsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['user', 'images', 'likes', 'comments', 'share'])->where('public', 1)->get();
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

    public function private () {
        $id= auth()->user()->id;
        $friends = Friends::with(['user'])->where('user_id', $id)->get();

        $sorted = collect($friends)->sortByDesc('id');
        $final  = [];
        foreach($sorted->values()->all() as $data){
            $final[] = $data->user;
        }
        $final[] = $id;
        
        $posts = [];
        foreach($final as $post) {
            $value = Post::with(['user', 'images', 'likes', 'comments', 'share'])->where('user_id', $post)->where('public', 0)->get();
            if(!is_null($value)) {
                foreach($value as $data){
                    $posts[]= $data;
                }
            }
        }

        $sorted = collect($posts)->sortByDesc('id');
        $final2  = [];
        foreach($sorted->values()->all() as $data){
            $final2[] = $data;
        }


        $response = [
            'post'=> $final2,
            'message'=> 'posts retrieved',
            'success' => true
        ];

        return response($response, 200);
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

    public function store(Request $request)
    {
            $user = Auth::user();

            if(is_null($request['public'])) {
                $post = Post::create([
                    'user_id'=> auth()->user()->id,
                    'text'=> $request['text'],
                    'public'=> true
                ]);
                
                $response = [
                    'post_id'=> $post->id,
                    'message'=> 'post created',
                    'success' => true
                ];
            }else {
                $post = Post::create([
                    'user_id'=> auth()->user()->id,
                    'text'=> $request['text'],
                    'public'=> $request['public']
                ]);
                
                $response = [
                    'post_id'=> $post->id,
                    'message'=> 'post created',
                    'success' => true
                ];
            }
            

            // event(new PostCreated($user, $post));
            // broadcast(new PostCreated($user, $post));            

            return response($response, 201);
    }

    public function update(Request $request, $id)
    {
        //
        $post = Post::find($id);
        $update = $request->all();

        foreach($update as $up) {
            $post->update($up);
        }

        
        return response()->json([
            'post'=> $post,
            'success' => true,
            'message' => 'Media Uploaded Successfully!!'
        ]);
    }

    public function destroy($id)
    {
        //
        $post = Post::destroy($id);
        if($post === 1) {
            $response = [
                'message'=> 'post deleted',
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
