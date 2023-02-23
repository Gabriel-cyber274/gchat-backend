<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Response;

class mediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $media = Media::all();
        $sorted = collect($media)->sortByDesc('id');
        $final  = [];
        foreach($sorted->values()->all() as $data){
            $final[] = $data;
        }
        $response = [
            'media'=> $final,
            'message'=> 'media retrieved',
            'success' => true
        ];

        return response($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        
        $this->validate($request, [
            'file'=> 'required|mimes:png,jpg,jpeg,mp4,mov,ogg,qt',
            'post_id'=> 'required|integer'
        ]);

        $files = $request->allFiles('file');

        foreach ($files as $key => $img) {
            $filename = request()->getSchemeAndHttpHost() . '/assets/post/media/' . time() . '.'. $img->extension();
            $img->move(public_path('/assets/post/media/'), $filename);


            $photos = Media::create([
                'file' => $filename
            ]);
            
            // $images = Media::all()->last();
            $posts = Post::where('id', $request->post_id)->get();

            // $images->posts()->attach($posts);
            $photos->posts()->attach($posts);
        }

        return response()->json([
            'success' => true,
            'message' => 'Media Uploaded Successfully!!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
