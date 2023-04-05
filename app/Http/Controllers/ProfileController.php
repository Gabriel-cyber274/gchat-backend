<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfilePic;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfilePic()
    {
        //
        $id= auth()->user()->id;
        $pic = ProfilePic::with('user')->where('user_id', $id)->get();
        
        $response = [
            'media'=> $pic,
            'message'=> 'pic retrieved',
            'success' => true
        ];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pic(Request $request)
    {
        //
        $this->validate($request, [
            'pic'=> 'required|mimes:png,jpg,jpeg',
        ]);
        
        $filename = request()->getSchemeAndHttpHost() . '/assets/profile/media/' . time() . '.'. $request->pic->extension();
        $request->pic->move(public_path('/assets/profile/media/'), $filename);
        
        $id= auth()->user()->id;

        $pic = ProfilePic::create([
            'pic' => $filename,
            'user_id'=> $id
        ]);

        if($pic) {
            return response()->json([
                'success' => true,
                'message' => 'Profile Uploaded Successfully!!'
            ]);
        }else {
            return response()->json([
                'success' => true,
                'message' => 'Profile Upload failed'
            ]);
        }
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
