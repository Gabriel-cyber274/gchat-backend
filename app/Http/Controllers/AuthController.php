<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Stories;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;
// use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function Register (Request $request) {
        $fields = $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|string|unique:users,email',
            'password'=> 'required|string|min:8'
        ]);

        $user = User::create([
            'name'=> $fields['name'],
            'email'=> $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('myToken')->plainTextToken;

        
        $stories = Stories::create([
            'user_id'=> $user->id,
        ]);

        $notification = Notification::create([
            'image'=> 'bot',
            'user_id'=> $user->id,
            'name'=> 'bot',
            'message'=> 'Welcome to Gchat',
            'page_id'=> 404,
            'read'=> false
        ]);
        $notification->user()->attach($user);

        $response = [
            'user'=> $user,
            'message'=> 'successful signup',
            'success' => true
        ];

        return response($response, 201);
    }

    public function Login (Request $request) {
        $fields = $request->validate([
            'email'=> 'required|string',
            'password'=> 'required|string'
        ]);

        $user = User::with('profilePic')->where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'incorrect credentials',
                'success' => false
            ], 401);
        }

        $token = $user->createToken('myToken')->plainTextToken;

        $response = [
            'user'=> $user,
            'token'=> $token,
            'message'=> 'logged in',
            'success' => true
        ];

        return response($response, 201);

    }

    public function Logout (Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message'=> 'logged out'
        ];
    }

    public function Users () {
        $id= auth()->user()->id;
        $users = User::with('profilePic')->get();
        $mainUsers = $users->filter(function($value, $key){
            return $value->id !== auth()->user()->id;
        });

        $main = [];
        foreach($mainUsers->values()->all() as $users) {
            $main[]=$users;
        }

        $sorted = collect($main)->sortByDesc('id');
        $final  = [];
        foreach($sorted->values()->all() as $data){
            $final[] = $data;
        }
        
        $response = [
            'users'=> $final,
            'message'=> 'users retrieved',
            'success' => true
        ];

        return response($response);
    }
}
