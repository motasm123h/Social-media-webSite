<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\post;
use App\Models\User;
use App\Models\Like;
use App\Notifications\LikeNotification;
use Illuminate\Support\Facades\Auth;


class LikeController extends Controller
{
    public function likeOrunlike($id)
    {
        $post = Post::where('id','=', $id)->first();

        if(!$post){
            return response()->json([
                'message' => 'post not found'
            ],403);
        }
        // if($post->user_id != auth()->user()->id){
        //     return response()->json([
        //         'message' => 'Permission denied'
        //     ],403);
        // }

        $like=$post->like()->where('user_id','=',auth()->user()->id)->first();

        if(!$like)
        {
            Like::create([
                'likeable_id' => $id,
                'likeable_type'=>post::class,
                'user_id' => auth()->user()->id
            ]);


        $post = Post::find($id);
        
        $user = User::find($post['user_id']);

        $user->notify(new LikeNotification(auth()->user()));

            return response()->json([
                'message' => 'post is liked ',
                'post' => $post,
            ],200);
        }
        
        $like->delete();
        return response()->json([
            'message' => 'post is unliked',
        ],200);
    }


    public function getALlLike($post_id){
        $post = Post::where('id','=',$post_id)->first();
        $post_likes = $post->likes()->pluck('user_id');

        $user =  User::select('id','name','profile_image')->
        whereIn('id',$post_likes)
        ->get();
        
        return response()->json([
            'users' => $user,
        ]);
    }

}
