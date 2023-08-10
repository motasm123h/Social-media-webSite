<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\Post;
// use DB;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('message.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('post.{post_id}.friend.{id}', function ($post_id,$user, $id) {
//     return (int) $user->id === (int) $id;
// });
// Broadcast::channel('post-events.{userId}', function ($user, $userId) {
//     // Check if the current user is friends with the user ID in the channel name
//     return $user->friends->contains($userId);
// });

Broadcast::channel('post.{post_id}.friend.{friendId}', function ($post_id , $user, $friendId) {
    // Check if the current user is friends with the user ID in the channel name
    return $user->friends->contains($friendId);
});


Broadcast::channel('post.{post_id}.friend',function ($user,$post_id) {
$post = Post::find($post_id);
if ($post ) {
    $post_owner = User::find($post->user_id);
    return $post_owner->friends->contains($user->id);
}
return false;

});
Broadcast::channel('post',function ($user) {
return true;
});

Broadcast::channel('search',function ($user) {
    if(auth()->user()->id == $user->id){
    return true;
    }
});





//comment
Broadcast::channel('post.{post_id}.user.{user_id}',function ($user,$post_id,$user_id) {
$post = Post::find($post_id);
     
        $user_type = User_HashTag::where('user_id','=',$user_id)->pluck('hashtag_id')->toArray();
        if($user_type){
            $check = $user_type->contains($hashtag_id);
            if($check){

            }
        }
});


Broadcast::channel('private-friend-request.{freind_id}', function ($user, $freind_id) {
    return (int) $user->id === (int) $freind_id;
});