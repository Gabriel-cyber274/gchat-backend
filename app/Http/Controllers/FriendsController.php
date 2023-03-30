<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friends;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Response;

class FriendsController extends Controller
{
    public function index()
    {
        $id= auth()->user()->id;
        $friends = Friends::with(['user'])->where('user', $id)->get();
        $sorted = collect($friends)->sortByDesc('id');
        $final  = [];
        foreach($sorted->values()->all() as $data){
            $final[] = $data;
        }
        $response = [
            'friends'=> $final,
            'message'=> 'friends retrieved',
            'success' => true
        ];

        return response($response, 200);

    }

    public function store(Request $request)
    {
        //
        
        $this->validate($request, [
            'user_id'=> 'required|integer',
        ]);

        
        // $check = Friends::where('user', auth()->user()->id)->where('user_id', $request['user_id'])->get()->filter(function($value, $key){
        //     return $value->user_id == auth()->user()->id;
        // });

        $check = Friends::where('user', auth()->user()->id)->where('user_id', $request['user_id'])->get();
        if(count($check) === 0) {
            $friend = Friends::create([
                'user'=> auth()->user()->id,
                'user_id'=> $request['user_id'],
            ]);
    
            
            $user = User::where('id', $request['user_id'])->get();
            $friend->user()->attach($user);
            
            
            $response = [
                'friend'=> $friend,
                'message'=> 'friend added',
                'success' => true
            ];
        }
        else {
            $response = [
                'message'=> 'this user is already your friend',
                'success' => true
            ];
        }


        
        return response($response, 201);
    }

    public function destroy($id, $id2)
    {
        //
        
        $friend = Friends::destroy($id);
        $user = User::where('id', $id2)->get();
        
        $text = 'You'.' '. 'unfriended'.' '.$user->first()->name;

        if($friend === 1) {
            $response = [
                'message'=> $text,
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
