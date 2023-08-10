<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Save;
use Illuminate\Support\Facades\Auth;
use App\Models\post;
use App\Models\User;

class SaveControllers extends Controller
{
    public function index(){
        $save=Save::where('user_id', '=',auth()->user()->id)->pluck('post_id')->toArray();

        $posts = post::select('id','user_id','text','image','hashtag_id','created_at')->withCount('comment')->withCount('like')->with(['user' => function($query){
            $query->select('id','name','profile_image');
        }])->whereIn('id',$save)->get();

        foreach ($posts as $key => $value) {
            $value->isLiked = $value->like()->where('user_id', auth()->user()->id)->exists();
        }

        foreach ($posts as $post) {
        $post->isSaved = $post->saves()->where('user_id', auth()->user()->id)->exists();
        }

        return response()->json([
            'post' => $posts,
        ]);

    }

    public function save_Post($post_id){
       
        $post = Post::where('id','=', $post_id)->first();
       
       if(!$post){
            return response()->json([
                'message' => 'post not found'
            ],403);
       }

       $save = $post->saves->where('user_id', auth()->user()->id)->first();
       
       if(!$save){
        $save = Save::create([
            'user_id' =>auth()->user()->id,
            'post_id' =>$post_id,
        ]);

        $posts = post::select('id','user_id','text','image','created_at','hashtag_id')->withCount('comment')->withCount('like')->with(['user' => function($query){
            $query->select('id','name','profile_image');
        }])->where('id',$post_id)->get();

        foreach ($posts as $key => $value) {
        $value->isLiked = $value->like()->where('user_id', auth()->user()->id)->exists();
        }

        foreach ($posts as $post) {
        $post->isSaved = $post->saves()->where('user_id', auth()->user()->id)->exists();
        }


        
        return response()->json([
            'post' => $posts,
        ]);

       }

       $save->delete();
       return response()->json([
            'post' => 'unsaved',
        ]);


        
        
    }


    public function delete_save($id){
        $save = Save::where('id','=',$id)->first();
        $save->delete();

        return response()->json([
            'message' =>true,
        ]);
    }
}
