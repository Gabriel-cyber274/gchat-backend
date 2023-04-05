<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stories;
use Illuminate\Http\Response;
use App\Models\Friends;
use App\Models\StoriesMedia;
use App\Models\StoriesText;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoriesController extends Controller
{
    //
    public function index() {
        $id= auth()->user()->id;
        $friends = Friends::with(['user'])->where('user_id', $id)->get();
        $sorted = collect($friends)->sortByDesc('id');

        $usershare  = [];
        foreach($sorted->values()->all() as $data){
            $usershare[] = $data->user;
        }
        // $usershare[] = $id;

        
        $MediaAll = StoriesMedia::with(['stories'])->get();

        $old = $MediaAll->filter(function($value, $key){
            // $day = substr(Str::limit($value->created_at, 10, ''), 8) + 1;
            // $date = Str::limit($value->created_at, 8, ''). $day . ' '. substr($value->created_at, 11);
            return $value->created_at <= now()->subHours(24);
        });

        foreach($old as $data){
            StoriesMedia::destroy($data->id);
        }

        

        
        $textAll = StoriesText::with(['stories'])->get();

        $old2 = $textAll->filter(function($value, $key){
            return $value->created_at <= now()->subHours(24);
        });

        foreach($old2 as $data){
            StoriesText::destroy($data->id);
        }
        

        // Str::limit($value->created_at, 10, '') == '20'.now()->format('y-m-d')
        // '20'.now()->format('y-m-d')
        

        $pub = [];
        if(count(Stories::with(['media','user', 'text'])->where('user_id', $id)->get()->first()->media) !== 0 || count(Stories::with(['media','user', 'text'])->where('user_id', $id)->get()->first()->text) !== 0) {
            $pub[]= Stories::with(['media','user', 'text'])->where('user_id', $id)->get()->first();
        }

        foreach($usershare as $data){
            if(count(Stories::with(['media','user', 'text'])->where('user_id', $data)->get()->first()->media) !== 0 || count(Stories::with(['media','user', 'text'])->where('user_id', $data)->get()->first()->text) !== 0) {
                $pub[]= Stories::with(['media','user', 'text'])->where('user_id', $data)->get()->first();
            }
        }
        

        
        $response = [
            'stories'=> $pub,
            'message'=> 'stories retrieved',
            'success' => true
        ];
        return response($response);
    }

    
}
