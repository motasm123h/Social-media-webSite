<?php

namespace App\Repositories;

use App\Models\Message;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;


class ChatRepository {

    //done
    public function getUserMessages($senderId, $receiverId ){
        // return Message::whereIn('sender_id',[$senderId,$receiverId])
        //        ->whereIn('receiver_id',[$senderId,$receiverId])
        //        ->get();



    //*************** */
       return 
       Message::where(function($query) use ($senderId, $receiverId) {
                    $query->where('sender_id', $senderId)
                          ->where('receiver_id', $receiverId);
                })
                ->orWhere(function($query) use ($senderId, $receiverId) {
                    $query->where('sender_id', $receiverId)
                          ->where('receiver_id', $senderId);
                })
                ->orderBy('created_at', 'asc')
                ->get();

    // return response()->json(['messages' => $messages]);    
    }



    public function sendMessage(array $date){
        return Message::create($date);
    }

    
    public function getRecentUsersWithMessage(int $senderId)
    {
        // DB::statement("SET SESSION sql_mode=''");

        // $recentMessages = Message::where(function ($query) use ($senderId) {
        //     $query->where('sender_id', $senderId)
        //         ->orWhere('receiver_id', $senderId);
        // })
        // ->groupBy('sender_id', 'receiver_id')
        // ->select('sender_id', 'receiver_id', 'message')
        // ->orderBy('id','desc')
        // ->limit(30)
        // ->get();

        // return $this->getFilterRecentMessages($recentMessages, $senderId);
        // return $recentMessages;


        // this test two
        // $messages = Message::where('receiver_id',$senderId)
        //             ->orderBy('created_at', 'DESC')
        //             ->get()
        //             ->unique('sender_id')
        //             ->values()
        //             ->all();


    //     $messages = DB::table('messages')
    //          ->join('users', function($join) use ($senderId) {
    //              $join->on('users.id', '=', 'messages.sender_id')
    //                 ->orWhere('users.id', '=', 'messages.receiver_id')
                 
    //                 ->where(function($query) use ($senderId) {
    //                      $query->where('messages.sender_id', '=', $senderId)
    //                            ->where('messages.receiver_id', '=', $senderId);
    //             })
    //                 ->orWhere(function($query) use ($senderId) {
    //                      $query->where('messages.sender_id', '=', $senderId)
    //                            ->where('messages.receiver_id', '=', $senderId);
    //             });
    //     })
    //     ->orderBy('messages.created_at', 'desc')
    //     ->select('users.id as friend_id', 'users.name as friend_name', 'messages.*')
    //     ->first();

    // if ($messages) {
    //     return response()->json([
    //         'id' => $messages->friend_id,
    //         'name' => $messages->friend_name,
    //         // 'image' => $messages->friend_image,
    //         'last_message' => $messages
    //     ]);
    // } else {
    //     return response()->json([]);
    // }


    //    return $friends=auth()->user()->getFriends();
       return $friends=User::all();


    }


    public function getAllUser(int $senderId){

        return User::Where('id','!=',$senderId)
        ->select('name' ,'id')
        ->get();

    }
    
    
    public function getFilterRecentMessages(Collection $recentMessages, int $senderId): array
    {
        $recentUsersWithMessage = [];
        $usedUserIds = [];
        foreach ($recentMessages as $message) {
            $userId = $message->sender_id == $senderId ? $message->receiver_id : $message->sender_id;
            if (!in_array($userId, $usedUserIds)) {
                $recentUsersWithMessage[] = [
                    'user_id' => $userId,
                    'message' => $message->message
                ];
                $usedUserIds[] = $userId;
            }
        }

        foreach ($recentUsersWithMessage as $key => $userMessage) {
            $recentUsersWithMessage[$key]['name'] = User::where('id', $userMessage['user_id'])->value('name') ?? '';
        }

        return $recentUsersWithMessage;
    }

}