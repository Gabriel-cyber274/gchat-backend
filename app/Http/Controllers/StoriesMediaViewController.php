<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Views;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoriesMediaViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $view = Views::with(['user'])->where('media_id', $id)->get();
        
        if(count($view) !== 0) {
            $response = [
                'viewers'=> $view,
                'message'=> 'post created',
                'success' => true
            ];
        }else {
            $response = [
                'viewers'=> 'none',
                'message'=> 'post created',
                'success' => true
            ];
        }

        return response($response);
    }
}
