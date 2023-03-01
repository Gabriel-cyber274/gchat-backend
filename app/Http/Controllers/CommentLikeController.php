<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentLike;
use App\Models\User;
use App\Models\Comments;
use Illuminate\Http\Response;

class CommentLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        
        $likes = CommentLike::with(['user'])->where("comment_id", $id)
        ->get();

        $response = [
            'likes'=> $likes,
            'message'=> 'comment likes retrieved successful',
            'success' => true
        ];

        return response($response);
    }

    public function store(Request $request)
    {
        //
        
        $id= Auth()->user()->id;
        
        $fields = $request->validate([
            'comment_id'=> 'required|integer',
        ]);

        
        $like = CommentLike::create([
            'comment_id'=> $request['comment_id'],
            'user_id'=> $id
        ]);
        
        $comment = Comments::where('id', $request->comment_id)->get();
        $like->comment()->attach($comment);

        $response = [
            'like'=> $like,
            'message'=> 'comment liked',
            'success' => true
        ];

        return response($response, 201);
        
    }

    public function destroy($id)
    {
        //
        
        $like = CommentLike::destroy($id);
        
        if($like === 1) {
            $response = [
                'message'=> 'comment unliked',
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
