<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Freind;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\FriendRequestNotification;
use App\Notifications\FriendRequestAcceptNotification;
class FreindShipController extends Controller
{
    public function SendFreindReuest($id){

        $freind=Freind::create([
            'user_id'=>auth()->user()->id,
            'freind_id'=>$id,
            'status' =>'pending',
         ]);
        $friend = Freind::where('id',$freind->id)->with('user')->first();
        $receiver = User::find($id);

        $receiver->notify(new FriendRequestNotification(auth()->user(),$freind));

        return response()->json(['freind' => $friend]);
    }


    public function AcceptFreind($id){
    

        $freind=Freind::where('id','=',$id)->first();

        $freind->update([
            'status' => 'accpted',
        ]);

        $receiver = User::find($freind['user_id']);
        // dd(auth()->user()->name);
        $receiver->notify(new FriendRequestAcceptNotification(auth()->user()));

       
       $freind_rev=Freind::create([
            'user_id'=>$freind->freind_id,
            'freind_id'=>$freind->user_id,
            'status' =>'accpted',
         ]);

        return response()->json(['freind' => true]);        
    }


    public function deleteFriend($id){
        $freind=Freind::where('id','=',$id)->first();
        $friend = Freind::where([
            'user_id'=>$freind['freind_id'],
            'freind_id'=>$freind['user_id']
            ]);
        $freind->delete();
        $friend->delete();
        return response()->json(['freind' => true]);
    }

    public function rejectFreind($id){
       

        $freind=Freind::where('id','=',$id)->first();
        $freind->update([
            'status'=>'rejected',
        ]);
        $user = User::where('id',$id)->first();

        return response()->json([
            'friend' => $user,
        ]);
    }
    

    public function getFreinds(){
        return response()->json(['freind'=>auth()->user()->getFriendsId()]);
    }

    public function getRandomfriend(){
        $friendsIds=auth()->user()->getFriendsIds();
        $randomUsers = User::whereNotIn('id',$friendsIds)
                ->where('id','!=',auth()->user()->id)
                ->inRandomOrder()
                ->take(4)
                ->get();

        return response()->json([
            'friend'=>$randomUsers,
        ]);
    }

    public function getSendRequest(){
    $friendRequests = auth()->user()->friendRequestsSent()->get();
    return response()->json([
            'sendRequest' => $friendRequests,
        ]);
    }


    public function getRecievedRequest(){

    $friendRequests = auth()->user()->friendRequestsReceived()->get();
    
        return response()->json([
            'RecievedRequest' => $friendRequests,
            'reci' => "yest"
        ]);
    }


}
