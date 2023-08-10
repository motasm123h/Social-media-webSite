<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\comment;
use App\Models\User;
use App\Models\Post;
use App\Events\Comment as CommentEvent;
use DB;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Auth;




class CommentController extends Controller
{

    public function index($post_id){
        
    $comments = Comment::select('id','comment','user_id','created_at','post_id')->with(['user' =>function($query){
            $query->select('id','name','profile_image');
        }])->get();

        return response()->json([
            'comment' => $comments
            ]); 

    }

        
    public function create(Request $request , $post_id){

        $atter = $request->validate([
            'comment' =>['required','string'],
        ]);

        $comment = Comment::create([
            'comment' =>$atter['comment'],
            'user_id'=>auth()->user()->id,
            'post_id'=>$post_id,
        ]);


        $back_comment = Comment::select('id','comment','user_id','created_at','post_id')->with(['user'=>function($query){
                $query->select('id','name','profile_image');
        }])->find($comment['id']);
        
        $post = Post::find($comment['post_id']);

        
        $user = User::find($post['user_id']);
        // dd($back_comment);
        // broadcast(new CommentEvent($back_comment,$post));

        $user->notify(new CommentNotification($back_comment));

        return response()->json([
            'comment' => $back_comment,
        ]);
    }

    public function edit(Request $request ,$comment_id){

        $comment=Comment::where('id','=',$comment_id)->first();
        //  $atter = $request->validate([
        //     'comment' =>['required','string'],
        // ]);
        $comment->update([
            'comment' =>$request->comment,
        ]);

        $back_comment = Comment::select('id','comment','user_id','created_at','post_id')->with(['user'=>function($query){
            $query->select('id','name','profile_image');
        }])->find($comment['id']);

        return response()->json([
            'comment' => $back_comment,
        ]);
    }

    public function delete($comment_id) 
    {
        $comment=Comment::where('id','=',$comment_id)->first();
        $result = $comment->delete();
        return response()->json([
            'message'=>$result,
        ]); 

    }
}
