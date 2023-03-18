<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoriesMedia;
use App\Models\Stories;
use App\Models\Views;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoriesMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        // $MediaAll = StoriesMedia::with(['stories'])->get();

        // $old = $MediaAll->filter(function($value, $key){
        //     $day = substr(Str::limit($value->created_at, 10, ''), 8) + 1;
        //     $date = Str::limit($value->created_at, 8, ''). $day . ' '. substr($value->created_at, 11);
        //     return $date <= '20'.now()->format('y-m-d H:i:s');
        // });

        // foreach($old as $data){
        //     StoriesMedia::destroy($data->id);
        // }
        
        // if(StoriesMedia::with(['stories', 'user', 'views'])->where('story_id', $id)->get()) {

        // }
        
        // $test = substr(Str::limit(Stories::get()->first()->created_at, 10, ''), 8) + 1;
        // $test2 = Str::limit(Stories::get()->first()->created_at, 8, ''). $test . ' '. substr(Stories::get()->first()->created_at, 11);
        
        if(StoriesMedia::with(['stories', 'user', 'views'])->where('story_id', $id)->get()->first()->user->id === auth()->user()->id) {
            $media = StoriesMedia::with(['stories', 'user', 'views'])->where('story_id', $id)->get();
            $response = [
                'media'=> $media,
                'message'=> 'stories retrieved',
                'success' => true
            ];
        }else {
            $media = StoriesMedia::with(['stories', 'user',])->where('story_id', $id)->get();
            $response = [
                'media'=> $media,
                'message'=> 'stories retrieved',
                'success' => true
            ];
        }

        return response($response); 
    }

    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'file'=> 'required|mimes:png,jpg,jpeg,mp4,mov,ogg,qt',
            // 'story_id'=> 'required|integer'
        ]);
        
        $id= auth()->user()->id;
        $storyId = Stories::where('user_id', $id)->get()->first()->id;

        $files = $request->allFiles('file');

        foreach ($files as $key => $img) {
            $filename = request()->getSchemeAndHttpHost() . '/assets/stories/media/' . time() . '.'. $img->extension();
            $img->move(public_path('/assets/stories/media/'), $filename);


            $photos = StoriesMedia::create([
                'file' => $filename,
                'story_id'=> $storyId,
                'user_id'=> $id,
            ]);
            
            // $images = Media::all()->last();
            $stories = Stories::where('id', $storyId)->get();

            // $images->posts()->attach($posts);
            $photos->stories()->attach($stories);
        }

        return response()->json([
            'success' => true,
            'message' => 'Media Uploaded Successfully!!'
        ]);
    }

    public function show($id)
    {
        //
        $media = StoriesMedia::with(['stories', 'user', 'views'])->where('id', $id)->get();
        
        $viewed = $media->first()->views->filter(function($value, $key){
            return $value->user_id == auth()->user()->id;
        });
        
        if(count($media) !== 0 && count($viewed) === 0) {
            $view = Views::create([
                'user_id'=> auth()->user()->id,
                'media_id'=> $id,
            ]);
            $user = User::where('id', auth()->user()->id)->get();

            $view->user()->attach($user);
        }
        
        $response = [
            'media'=> $media,
            'message'=> 'post created',
            'success' => true
        ];
        return response($response);
        
    }


    public function destroy($id)
    {
        $media = StoriesMedia::destroy($id);
        if($media === 1) {
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
