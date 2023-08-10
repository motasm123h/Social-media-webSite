<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use DB;
use App\Events\SearchEvent;


class SearchController extends Controller
{
    public function search($text){
        
$posts = DB::table('posts')
    ->select('posts.*', DB::raw('JSON_OBJECT(
        "id", users.id,
        "name", users.name,
        "profile_image", users.profile_image,
        "cover_image", users.cover_image,
        "online", users.online,
        "address", users.address
    ) AS user_json'))
    ->join('users', 'posts.user_id', '=', 'users.id')
    ->where('posts.text', 'like', '%'.$text.'%')
    ->get();

foreach ($posts as $post) {
    $post->user = json_decode($post->user_json);
    unset($post->user_json);
}


$users = DB::table('users')
    ->select('users.id','users.name','users.email','users.Authentication_mark','users.profile_image')
    ->where('users.name', 'like', '%'.$text.'%')->get();


  $result = [
            'user'=>$users,
            'posts'=>$posts
            ];


    broadcast(new SearchEvent($result));

    return response()->json([
        'result' => $result
    ]);
    }
}
