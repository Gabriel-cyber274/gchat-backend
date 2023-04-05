<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Response;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($receiverid)
    {
        //
        $id= auth()->user()->id;
        if($receiverid > $id) {
            $chat = Chat::with('user')->where('channel', $receiverid.$id)->get();
            $response = [
                'chat'=> $chat,
                'message'=> 'chat retrieved',
                'success' => true
            ];
            return response($response);
        }else {
            $chat = Chat::with('user')->where('channel', $id.$receiverid)->get();
            $response = [
                'chat'=> $chat,
                'message'=> 'chat retrieved',
                'success' => true
            ];
            return response($response);
        }
        
    }

    public function store(Request $request)
    {
        //
        $id= auth()->user()->id;
        $this->validate($request, [
            'receiver_id'=> 'required|integer',
        ]);
        if($request->receiver_id > $id) {
            $chat = Chat::create([
                'receiver_id' => $request->receiver_id,
                'sender_id'=> $id,
                'channel'=> $request->receiver_id.$id,
                'message'=> $request->message,
            ]);
            
            return response()->json([
                'chat'=> $chat,
                'success' => true,
                'message' => 'message sent'
            ]);
        }else {
            $chat = Chat::create([
                'receiver_id' => $request->receiver_id,
                'sender_id'=> $id,
                'channel'=> $id.$request->receiver_id,
                'message'=> $request->message,
            ]);
            
            return response()->json([
                'chat'=> $chat,
                'success' => true,
                'message' => 'message sent'
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
