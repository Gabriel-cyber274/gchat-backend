<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $id=auth()->user()->id;
        $Notification = Notification::with('user')->get();

        $users = [];
        foreach($Notification->values()->all() as $data){
            $filter = $data->user->filter(function($value2, $key2){
                return $value2->id === auth()->user()->id;
            });
            if(count($filter) !== 0) {
                $users[] = $filter->first();
            }
        }

        $ids = [];
        foreach($users as $data){
            $ids[] = $data->pivot->notification_id;
        }

        $notificationMain = Notification::with('user')->find($ids);
        
        $sorted = collect($notificationMain)->sortByDesc('id');
        $final  = [];
        foreach($sorted->values()->all() as $data){
            $final[] = $data;
        }
        $response = [
            'notification'=> $final,
            'message'=> 'Notification retrieved',
            'success' => true
        ];

        return response($response, 200);
    }

    public function update(Request $request, $id)
    {
        //
        $notification = Notification::with('user')->find($id);
        $update = $request->all();

        foreach($update as $up) {
            $notification->update($up);
        }

        
        return response()->json([
            'notification'=> $notification,
            'success' => true,
            'message' => 'Updated sucessfully'
        ]);
    }

    
    public function updateAll(Request $request)
    {
        //
        $id=auth()->user()->id;
        $Notification = Notification::with('user')->get();

        $users = [];
        foreach($Notification->values()->all() as $data){
            $filter = $data->user->filter(function($value2, $key2){
                return $value2->id === auth()->user()->id;
            });
            if(count($filter) !== 0) {
                $users[] = $filter->first();
            }
        }

        $ids = [];
        foreach($users as $data){
            $ids[] = $data->pivot->notification_id;
        }

        $notification = Notification::with('user')->find($ids);
        $update = $request->all();
        foreach($update as $up) {
            foreach($notification as $not) {
                $not->update($up); 
            }
        }

        
        return response()->json([
            'notification'=> $notification,
            'success' => true,
            'message' => 'Marked all as Read'
        ]);
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
        $notification = Notification::destroy($id);
        
        if($notification === 1) {
            $response = [
                'message'=> 'notification deleted',
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
