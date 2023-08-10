<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;


class AdminController extends Controller
{
    public function index()
    {
        $users_num = User::count();
        $post_num = Post::count();
        return response()->json([
            'user_number' =>$users_num ,
            'post_number' =>$post_num ,
            ]);
    }
    
    public function getAllUser(){
        $users=User::select('id','name','email','profile_image')->get();
        return response()->json([
            'users'=>$users
        ]);
    }

    public function getUserPost($user_id){
         $user = User::find($user_id);
         $posts = Post::select('id', 'user_id', 'text', 'image')
            ->where('user_id', $user_id)
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'profile_image', 'cover_image');
            }])
            ->withCount('comment')
            ->get();

        return response()->json([
            'user' =>$user,
            'posts'=>$posts,
            ]); 
    }

    public function getposts(){
        $posts = Post::with(['user' => function ($query) {
            $query->select('id', 'name', 'profile_image', 'cover_image');
        }])->get();
        return response()->json([
            'post' =>$posts ,
            ]);
    }

    public function getfilterposts($post_type_id){
        $posts = Post::select('id', 'user_id', 'text', 'image','hashtag_id')
            ->where('hashtag_id', $post_type_id)
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'profile_image', 'cover_image');
            }])
            ->withCount('comment')
            ->get();
        return response()->json([
            'posts' => $posts
        ]);    
    }

    public function deleteUser($id){
        $user =User::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'message' => 'true',
            ]);
        }
        else{
            return response()->json([

                'message' => "User Not Found "
            ]);
        }
    }

    public function AuthUser($id){
        $user=User::find($id);
        if($user){
            $user->update([
                'name' => 'rr',
                'Authentication_mark' => 1
            ]);
            return response()->json([

                'message' => $user
            ]);
        }

        else{
           return response()->json([

                'message' => "User Not Found "
            ]);
        }   
    }


}
