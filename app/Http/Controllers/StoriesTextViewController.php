<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ViewsText;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoriesTextViewController extends Controller
{
    //
    public function index($id)
    {
        //
        $view = ViewsText::with(['user'])->where('text_id', $id)->get();
        
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
