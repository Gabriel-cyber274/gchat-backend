<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoriesText;
use App\Models\Stories;
use App\Models\User;
use App\Models\ViewsText;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoriesTextController extends Controller
{
    //
    
    public function index($id)
    {
        //
        $textAll = StoriesText::with(['stories'])->get();

        $old2 = $textAll->filter(function($value, $key){
            $day = substr(Str::limit($value->created_at, 10, ''), 8) + 1;
            $date = Str::limit($value->created_at, 8, ''). $day . ' '. substr($value->created_at, 11);
            return $date <= '20'.now()->format('y-m-d H:i:s');
        });

        foreach($old2 as $data){
            StoriesText::destroy($data->id);
        }


        if(count(StoriesText::with(['stories', 'user', 'views'])->where('story_id', $id)->get()) > 0 && StoriesText::with(['stories', 'user', 'views'])->where('story_id', $id)->get()->first()->user->id === auth()->user()->id) {
            $text = StoriesText::with(['stories', 'user', 'views'])->where('story_id', $id)->get();
            $response = [
                'text'=> $text,
                'message'=> 'stories retrieved',
                'success' => true
            ];
        }else {
            $text = StoriesText::with(['stories', 'user',])->where('story_id', $id)->get();
            $response = [
                'text'=> $text,
                'message'=> 'stories retrieved',
                'success' => true
            ];
        }

        // $test = substr(Str::limit(Stories::get()->first()->created_at, 10, ''), 8) + 1;
        // $test2 = Str::limit(Stories::get()->first()->created_at, 8, ''). $test . ' '. substr(Stories::get()->first()->created_at, 11);

        return response($response); 
    }
    
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'text'=> 'required|string|max:500',
            // 'story_id'=> 'required|integer'
        ]);
        
        $id= auth()->user()->id;
        $storyId = Stories::where('user_id', $id)->get()->first()->id;
        
        $text = StoriesText::create([
            'text' => $request['text'],
            'story_id'=> $storyId,
            'user_id'=> $id,
        ]);
        
        $stories = Stories::where('id', $storyId)->get();

        $text->stories()->attach($stories);

        return response()->json([
            'success' => true,
            'message' => 'posted Successfully!!'
        ]);
    }

    public function show($id)
    {
        //
        $text = StoriesText::with(['stories', 'user', 'views'])->where('id', $id)->get();
        
        $viewed = $text->first()->views->filter(function($value, $key){
            return $value->user_id == auth()->user()->id;
        });
        
        if(count($text) !== 0 && count($viewed) === 0) {
            $view = ViewsText::create([
                'user_id'=> auth()->user()->id,
                'text_id'=> $id,
            ]);
            $user = User::where('id', auth()->user()->id)->get();

            $view->user()->attach($user);
        }
        
        $response = [
            'text'=> $text,
            'message'=> 'stories retrieved',
            'success' => true
        ];
        return response($response);
        
    }

    
    public function destroy($id)
    {
        $text = StoriesText::destroy($id);
        if($text === 1) {
            $response = [
                'message'=> 'story deleted',
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
