<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

        $response = [
            'user'=> $user,
            // 'token'=> $token,
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

        $user = User::where('email', $fields['email'])->first();

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
}
