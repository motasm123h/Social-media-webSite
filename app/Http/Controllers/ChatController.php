<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ChatRepository;
use Illuminate\Support\Facades\Auth;
use App\Events\Message;

class ChatController extends Controller
{
    public function __construct(private ChatRepository $chat) {
        $this->chat = $chat;
    }

    // done
    public function getMessage($receiverId)
    {
        // dd($receiverId);
        // $message = empty($receiverId) ? [] : $this->chat->getUserMessages(auth()->user()->id, $receiverId);
        return response()->json([
            'message' => $this->chat->getUserMessages(auth()->user()->id, $receiverId),
            'id'=>$receiverId
        ]);
    }


    public function getUsesrWithLastMessage(){
        return response()->json([
            // 'recentMessages' => $this->chat->getAllUser(auth()->user()->id)
            'recentMessages' => $this->chat->getRecentUsersWithMessage(auth()->user()->id)
        ]);
    }

    //done
    public function snedMessage(Request $request ,$receiverId){
        $request->validate([
            'message' => ['required','string'],
        ]);

        if(empty($receiverId)){
            return ;
        }

        try{
            $message = $this->chat->sendMessage([
            'message' => $request->message,
            'sender_id'=>auth()->user()->id,
            'receiver_id' => $receiverId,
    
            ]);
    //    Message::dispatch($message);
        broadcast(new Message($message));

        return response()->json([
            'message' => $message,
        ]);
        

        }
        catch(e){
            return e.message;
        }
        
    }
}
