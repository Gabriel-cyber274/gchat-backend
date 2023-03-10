<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Save;
use App\Models\User;
use App\Models\Post;
// use App\Models\Friends;
use Illuminate\Http\Response;

class saveController extends Controller
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

        $save = Save::with(['post'])->where('user_id', $id)->get();

        $try=[];
        foreach($save as $sha) {
            $try[]=$sha->post->id;
        }
        
        $posts = Post::with(['user', 'images', 'likes', 'comments', 'share'])->find($try);
        $sorted = collect($posts)->sortByDesc('id');
        $final  = [];
        foreach($sorted->values()->all() as $data){
            $final[] = $data;
        }

        $response = [
            'post'=> $final,
            'message'=> 'saved post retrieved',
            'success' => true
        ];

        return response($response);
    }

    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'post_id'=> 'required|integer',
        ]);

        
        $save = Save::create([
            'user_id'=> auth()->user()->id,
            'post_id'=> $request['post_id'],
        ]);
        
        $user = User::where('id', auth()->user()->id)->get();
        $save->user()->attach($user);
        
        $response = [
            'save'=> $save,
            'message'=> 'post saved',
            'success' => true
        ];

        return response($response);
    }

    public function destroy($id)
    {
        //
        $user= auth()->user()->id;

        $save = Save::with(['post'])->where('user_id', $user)->where('post_id', $id)->get();

        $delete = Save::destroy($save);
        
        if($delete === 1) {
            $response = [
                'message'=> 'deleted successfully',
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
