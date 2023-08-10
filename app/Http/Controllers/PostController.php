<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostNotification;
// use App\Notifications\PostNotification;
use App\Events\Post as PostEvents;
use DB;
use App\Models\User_HashTag;


class PostController extends Controller
{
    private $hastag=[
        [ 'key' => '1', 'value'=> '# Sport' ],
        [ 'key' => '2', 'value'=> '# News' ],
        [ 'key' => '3', 'value'=> '# Food' ],
        [ 'key' => '4', 'value'=> '# Music' ],
        [ 'key' => '5', 'value'=> '# Dance' ],
        [ 'key' => '6', 'value'=> '# Memes' ],
        [ 'key' => '7', 'value'=> '# funny' ],
        [ 'key' => '8', 'value'=> '# Love' ],
        [ 'key' => '9', 'value'=> '# Happy' ],
        [ 'key' => '10', 'value'=> '# Fashion' ],
        [ 'key' => '11', 'value'=> '# Comedy' ],
        [ 'key' => '12', 'value'=> '# Prank' ],
        [ 'key' => '13', 'value'=> '# Friends' ],
        [ 'key' => '14', 'value'=> '# Cooking' ],
        [ 'key' => '15', 'value'=> '# Travel' ],
        [ 'key' => '16', 'value'=> '# Animals' ],
    ];


    public function index()
    {

        
        $user=auth()->user();
        $user_type = User_HashTag::where('user_id','=',$user->id)->pluck('hashtag_id')->toArray();
        

       $posts = Post::select('id','user_id','text','image','created_at','hashtag_id')->withCount('comment')->withCount('like')->with(['user' => function($query){
            $query->select('id','name','profile_image');
        }])->whereIn('hashtag_id',$user_type)->get();

        foreach ($posts as $key => $value) {
            $value->isLiked = $value->like()->where('user_id', $user->id)->exists();
        }
        foreach ($posts as $post) {
        $post->isSaved = $post->saves()->where('user_id', auth()->user()->id)->exists();
        }
        foreach ($posts as $post) {
            foreach ($this->hastag as $item) {
                if ($item['key'] == $post->hashtag_id) {
                    $post->type = $item['value'];
                    break;
                }
            }
        }

        return response()->json([
                    'post' => $posts,
                    ]);
    }



    public function postByType($type){


        $posts = Post::select('id','user_id','text','image','created_at','hashtag_id')->withCount('comment')->withCount('like')->with(['user' => function($query){
            $query->select('id','name','profile_image');
        }])->where('hashtag_id',$type)->get();

        foreach ($posts as $key => $value) {
            $value->isLiked = $value->like()->where('user_id', auth()->user()->id)->exists();
        }
        foreach ($posts as $post) {
        $post->isSaved = $post->saves()->where('user_id', auth()->user()->id)->exists();
        }
    
        foreach ($posts as $post) {
            foreach ($this->hastag as $item) {
                if ($item['key'] == $post->hashtag_id) {
                    $post->type = $item['value'];
                    break;
                }
        }

        return response()->json([
                    'post' => $posts,
                    ]);

    }

    }

    //done
    public function Post_Profail($user_id)
    {

        if($user_id == auth()->user()->id)
        {

        $user=User::where('id','=',$user_id)->select('name','profile_image','cover_image','online','address')->get();

        $posts = Post::select('id', 'user_id', 'text', 'image','created_at','hashtag_id')
            ->where('user_id', $user_id)
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'profile_image');
            }])
            ->withCount('comment')
            ->withCount('like')
            ->get();

        foreach ($posts as $key => $value) {
            $value->isLiked = $value->like()->where('user_id', $user_id)->exists();
        }
        foreach ($posts as $post) {
        $post->isSaved = $post->saves()->where('user_id', auth()->user()->id)->exists();
        }

        foreach ($posts as $post) {
            foreach ($this->hastag as $item) {
                if ($item['key'] == $post->hashtag_id) {
                    $post->type = $item['value'];
                    break;
                }
        }
        }


         return response()->json([
            "user"=>$user,
            'posts'=>$posts,
            ]);   

        }

        else
        {
        
        $id=auth()->user()->id;
        $user = User::where('id', '=', $user_id)
        ->select('id','name', 'profile_image','cover_image', 'online', 'address')
        ->with(['friends' => function ($query) use ($id) {
        $query->where('freinds.status', 'accpted')
            ->where('freinds.user_id', $id);
        }])
        
        ->first();

        $user->isFriend = $user->friends->isNotEmpty();
        unset($user->friends);

        $posts = Post::select('id', 'user_id', 'text', 'image','created_at','hashtag_id')
            ->where('user_id', $user_id)
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'profile_image');
            }])
            ->withCount('comment')
            ->withCount('like')
            ->get();


        foreach ($posts as $key => $value) {
            $value->isLiked = $value->like()->where('user_id', $user_id)->exists();
        }
        foreach ($posts as $post) {
        $post->isSaved = $post->saves()->where('user_id', auth()->user()->id)->exists();
        }
        foreach ($posts as $post) {
            foreach ($this->hastag as $item) {
                if ($item['key'] == $post->hashtag_id) {
                    $post->type = $item['value'];
                    break;
                }
        }}

        $users = [$user];
         return response()->json([
            "user"=>$users,
            'posts'=>$posts,
            ]); 

        }

    }

    public function Image_Upload(Request $request)
    {
    $atter = $request->validate([        
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'type' => 'required',
        ]);
    $image_name = $atter['name'];
    if($atter['type'] == 'posts'){
    $imagePath=$request->file('image')->move(public_path('posts'),$image_name);
    }
    else if($atter['type'] == 'user'){
    $imagePath=$request->file('image')->move(public_path('user'),$image_name);
    }

    return response()->json([
        'message' => true,
        ]);
    }


    public function CreatePost(Request $request)
    {
        $atter = $request->validate([
            'text' => ['required','string'],
            'type' => ['required','integer'],
        ]);

         $post=Post::create([
            'user_id'=>auth()->user()->id,
            'text' => $atter['text'],
            'image' => $request->image,
            'hashtag_id' => $request->type,
         ]);  
        
        $back_post = Post::select('id','user_id','text','image','created_at','hashtag_id')->withCount('like')
        ->withCount('comment')->with(['user' => function($query){
            $query->select('id','name','profile_image');
        }])->where('id', '=', $post->id)->first();
        
         $back_post->isLiked = $back_post->like()->where('user_id', auth()->user()->id)->exists();
        
         foreach ($this->hastag as $item) {
                if ($item['key'] == $back_post->hashtag_id) {
                    $back_post->type = $item['value'];
                    break;
                }
         }
        // broadcast(new PostEvents($back_post));


         return response()->json([
            'post' => $back_post,
         ]);

    }    

    public function UpdatePost(Request $request , $id){
        $post=Post::where('id','=',$id)->first();
        $atter = $request->validate([
            'text' => ['required','string'],
            'type' => ['required','integer'],
        ]);
        $post->update([
            'text' =>$atter['text'],
        ]);

        $back_post=Post::select('id','user_id','text','created_at','image','hashtag_id')->withCount('like')
        ->withCount('comment')->with(['user' => function($query){
            $query->select('id','name','profile_image');
        }])->where('id','=',$post->id)->first();

        foreach ($this->hastag as $item) {
                if ($item['key'] == $back_post->hashtag_id) {
                    $back_post->type = $item['value'];
                    break;
                }
         }


        return response()->json([
            'post' => $back_post,
        ]);
    }

    public function DeletePost($id){
        $post=Post::where('id','=',$id)->first();
        $post->delete();
        return response()->json([
            'message' => 'delete success' ,
        ]);

    }
}
