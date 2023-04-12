<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcomment;
use App\Models\User;
use App\Models\Comments;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class SubCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        
        $subcomment = Subcomment::with(['user'])->where("comment_id", $id)
        ->get();

        $response = [
            'subcomment'=> $subcomment,
            'message'=> 'subcomments retrieved successful',
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
            'comment'=> 'required|string'
        ]);

        
        $subcomment = Subcomment::create([
            'comment_id'=> $request['comment_id'],
            'user_id'=> $id,
            'comment'=> $request->comment
        ]);
        
        $comment = Comments::where('id', $request->comment_id)->get();
        $subcomment->comment()->attach($comment);

        if($comment->first()->user_id !== auth()->user()->id) {
            $user = User::with('profilePic')->where('id', $comment->first()->user_id)->get();
            $user_not = User::with('profilePic')->where('id', auth()->user()->id)->get()->first();
    
            $notification = Notification::create([
                'image'=> $user_not->profilePic,
                'user_id'=> $comment->first()->user_id,
                'name'=> $user_not->name,
                'message'=> 'replied your comment.'. ' '.'('.Str::limit($request->comment, 20, '...').')',
                'page_id'=> $comment->first()->post_id,
                'read'=> false
            ]);
            $notification->user()->attach($user);
        }

        $response = [
            'subcomment'=> $subcomment,
            'message'=> 'subcomment created',
            'success' => true
        ];

        return response($response, 201);
    }

    public function destroy($id)
    {
        //
    }
}
