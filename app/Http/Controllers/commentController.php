<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comments;
use App\Models\Post;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class commentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //

        $comments = Comments::with(['user', 'commentlike', 'subcomments'])->where("post_id", $id)
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

        if($posts->first()->user_id !== auth()->user()->id) {
            $user = User::with('profilePic')->where('id', $posts->first()->user_id)->get();
            $user_not = User::with('profilePic')->where('id', auth()->user()->id)->get()->first();
    
            $notification = Notification::create([
                'image'=> $user_not->profilePic,
                'user_id'=> $posts->first()->user_id,
                'name'=> $user_not->name,
                'message'=> 'commented on your post'. ' '.'('.Str::limit($request->comment, 20, '...').')',
                'page_id'=> $request->post_id,
                'read'=> false
            ]);
            $notification->user()->attach($user);
        }

        

        $response = [
            'commentID'=> $comments->id,
            'message'=> 'comment successful',
            'success' => true
        ];

        return response($response, 201);
    }

    public function destroy($id)
    {
        //
        $comment = Comments::destroy($id);
        
        if($comment === 1) {
            $response = [
                'message'=> 'Comment deleted',
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
