<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friends;
use App\Models\User;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Response;

class FriendsController extends Controller
{
    public function index()
    {
        $id= auth()->user()->id;
        $friends = Friends::where('user', $id)->get();
        $users = [];
        foreach($friends->values()->all() as $data){
            $users[] = $data->user_id;
        }



        $sorted = collect(User::with('profilePic')->find($users))->sortByDesc('id');
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

    public function confirm()
    {
        $id= auth()->user()->id;
        $friends = Friends::where('user_id', $id)->get();
        $users = [];
        foreach($friends->values()->all() as $data){
            $users[] = $data->user;
        }



        $sorted = collect(User::with('profilePic')->find($users))->sortByDesc('id');
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
        $check2 = Friends::where('user_id', auth()->user()->id)->where('user', $request['user_id'])->get();
        $user_not = User::with('profilePic')->where('id', auth()->user()->id)->get()->first();
        if(count($check) === 0 && count($check2) === 0 ) {
            $friend = Friends::create([
                'user'=> auth()->user()->id,
                'user_id'=> $request['user_id'],
            ]);
    
            
            $user = User::with('profilePic')->where('id', $request['user_id'])->get();
            $friend->user()->attach($user);

            
            $user_not = User::with('profilePic')->where('id', auth()->user()->id)->get()->first();

            $notification = Notification::create([
                'image'=> $user_not->profilePic,
                'user_id'=> $request['user_id'],
                'name'=> $user_not->name,
                'message'=> 'sent you a friend request',
                'page_id'=> $user_not->id,
                'read'=> false
            ]);
            $notification->user()->attach($user);

            
            
            $response = [
                'friend'=> $friend,
                'message'=> 'friend added',
                'success' => true
            ];
        }
        else if (count($check) === 0 && count($check2) !== 0) {
            $friend = Friends::create([
                'user'=> auth()->user()->id,
                'user_id'=> $request['user_id'],
            ]);
    
            
            $user = User::with('profilePic')->where('id', $request['user_id'])->get();
            $friend->user()->attach($user);

            
            $user_not = User::with('profilePic')->where('id', auth()->user()->id)->get()->first();

            $notification = Notification::create([
                'image'=> $user_not->profilePic,
                'user_id'=> $request['user_id'],
                'name'=> $user_not->name,
                'message'=> 'accepted your friend request',
                'page_id'=> $user_not->id,
                'read'=> false
            ]);
            $notification->user()->attach($user);

            
            
            $response = [
                'friend'=> $friend,
                'message'=> 'friend request accepted',
                'success' => true
            ];
        }
        else {
            $response = [
                'message'=> 'this user is already your friend',
                'success' => false
            ];
        }

        return response($response, 201);
    }

    public function destroy($userid)
    {
        //
        $id= auth()->user()->id;
        $getFriend = Friends::where('user_id', $userid)->where('user', $id)->get();
        $friend = Friends::destroy($getFriend);
        $user = User::where('id', $userid)->get();
        
        $text = 'You'.' '. 'unfriended'.' '.$user->first()->name;
        $user_not = User::with('profilePic')->where('id', auth()->user()->id)->get()->first();

        if($friend === 1) {
            $response = [
                'message'=> $text,
                'success' => true
            ];

            $notification = Notification::create([
                'image'=> $user_not->profilePic,
                'user_id'=> $user->first()->id,
                'name'=> $user_not->name,
                'message'=> 'unfriended you',
                'page_id'=> $user_not->id,
                'read'=> false
            ]);
            $notification->user()->attach($user);
            
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
