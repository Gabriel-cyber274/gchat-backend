<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Share;
use App\Models\User;
use App\Models\Post;
use App\Models\Friends;
use Illuminate\Http\Response;

class shareController extends Controller
{
    public function index()
    {
        //
        
        $id= auth()->user()->id;

        $share = Share::with(['post'])->where('user_id', $id)->get();

        $try=[];
        foreach($share as $sha) {
            $try[]=$sha->post->id;
        }
        
        $posts = Post::with(['user', 'images', 'likes', 'comments', 'share'])->find($try);
        // $sorted = collect($posts)->sortByDesc('id');
        // $final  = [];
        // foreach($sorted->values()->all() as $data){
        //     $final[] = $data;
        // }

        $response = [
            'post'=> $posts,
            'message'=> 'shared post retrieved',
            'success' => true
        ];

        return response($response);

    }

    public function publicShare() {
        $user = User::has('share')->with('share')->get();
        
        $usershare=[];
        foreach($user as $share) {
            foreach($share->share as $share2) {
                $usershare[]=$share2->id;
            }
        }

        
        $shares = Share::with(['post'])->find($usershare);
        

        $main=[];
        $pub = $shares->filter(function($value, $key){
            return $value->public == 1;
        });
        
        foreach($pub->values()->all() as $share) {
            $main[]=$share;
        }

        $prepost=[];
        foreach($main as $post) {
            $prepost[]=$post->post->id;
        }

        
        $posts = Post::with(['user', 'images', 'likes', 'comments', 'share'])->find($prepost);
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
    
    public function privateShare() {
        $id= auth()->user()->id;
        $friends = Friends::with(['user'])->where('user_id', $id)->get();
        $sorted = collect($friends)->sortByDesc('id');

        $usershare  = [];
        foreach($sorted->values()->all() as $data){
            $usershare[] = $data->user;
        }
        $usershare[] = $id;

        
        $shareAll = Share::with(['post'])->get();
        $filtered = $shareAll->filter(function($value, $key){
            return $value->public == 0;
        });

        
        $shares = [];
        foreach($usershare as $data){
            $filt = collect($filtered)->where('user_id', $data)->all();
            if(!is_null($filt)) {
                foreach($filt as $data){
                    $shares[]= $data;
                }
            }
        }
        

        $prepost=[];
        foreach($shares as $post) {
            $prepost[]=$post->post->id;
        }

        
        $posts = Post::with(['user', 'images', 'likes', 'comments', 'share'])->find($prepost);
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

    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'post_id'=> 'required|integer',
        ]);

        if(is_null($request['public'])) {
            $share = Share::create([
                'user_id'=> auth()->user()->id,
                'post_id'=> $request['post_id'],
                'public'=> true,
            ]);
        }else {
            $share = Share::create([
                'user_id'=> auth()->user()->id,
                'post_id'=> $request['post_id'],
                'public'=> $request['public'],
            ]);
        }

        
        $user = User::where('id', auth()->user()->id)->get();
        $share->user()->attach($user);
        
        
        $response = [
            'share'=> $share,
            'message'=> 'post shared',
            'success' => true
        ];

        return response($response);

    }

    public function destroy($id)
    {
        //
        
        $user= auth()->user()->id;

        $share = Share::with(['post'])->where('user_id', $user)->where('post_id', $id)->get();

        $delete = Share::destroy($share);
        
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
