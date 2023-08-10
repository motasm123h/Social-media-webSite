<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FreindShipController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SaveControllers;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoriesController;
use App\Http\Controllers\VideoController;
use App\Events\Hello;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Broadcast::routes(['middleware' => ['auth:sanctum']]);



Route::post("register",[AuthController::class,'register']);
Route::post("login",[AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function(){
   
    Route::post('logout',[AuthController::class,'logout']);
    Route::post('UpdateInfo',[AuthController::class,'UpdateInfo']);
    Route::post('deleteAccuont',[AuthController::class,'deleteAccuont']);
   
    Route::post('user/imageUpload',[AuthController::class,'uploadImage']);
    Route::post('user/HashTag',[AuthController::class,'HashTag']);
    Route::post('user/Type',[AuthController::class,'Type']);
   
    Route::get('getMessages/{receiverId?}',[ChatController::class,'getMessage']);
    Route::get('getUserWithMessages',[ChatController::class,'getUsesrWithLastMessage']);
    Route::post('snedMessage/{receiverId?}',[ChatController::class,'snedMessage']);
   
    Route::post('sendfreind/{id}',[FreindShipController::class,'SendFreindReuest']);
    Route::post('accepptfreind/{id}',[FreindShipController::class,'AcceptFreind']);
    Route::post('rejectedfreind/{id}',[FreindShipController::class,'rejectFreind']);
    Route::get('getRandomfriend',[FreindShipController::class,'getRandomfriend']);
    Route::get('getfreind',[FreindShipController::class,'getFreinds']);
    Route::get('getSendRequest',[FreindShipController::class,'getSendRequest']);
    Route::get('getRecievedRequest',[FreindShipController::class,'getRecievedRequest']);
    Route::post('deleteFriend/{id}',[FreindShipController::class,'deleteFriend']);


    Route::get('post/index',[PostController::class,'index']);
    Route::post('post/imageUpload',[PostController::class,'Image_Upload']);
    Route::get('post/profail/{user_id}',[PostController::class,'Post_Profail']);
    Route::post('post/create',[PostController::class,'CreatePost']);
    Route::post('post/update/{post_id}',[PostController::class,'UpdatePost']);
    Route::delete('post/delete/{post_id}',[PostController::class,'DeletePost']);
    Route::get('post/type/{hashtag_type}',[PostController::class,'postByType']);
   
    Route::get('post/{post_id}/comment',[CommentController::class,'index']);
    Route::post('post/comment/create/{post_id}',[CommentController::class,'create']);
    Route::post('post/comment/update/{comment_id}',[CommentController::class,'edit']);
    Route::post('post/comment/delete/{comment_id}',[CommentController::class,'delete']);

    Route::post('post/like/create/{post_id}',[LikeController::class,'likeOrunlike']);
    Route::get('post/like/index/{post_id}',[LikeController::class,'getALlLike']);


    Route::get('getNotifications',[NotificationController::class,'getNotifications']);
    Route::post('markAsRead/{id}',[NotificationController::class,'markNotificationsAsRead']);
    Route::post('deleteNotification/{id}',[NotificationController::class,'deleteNotification']);

    Route::get('post/save',[SaveControllers::class,'index']);
    Route::post('post/save/{Postid}',[SaveControllers::class,'save_Post']);
    Route::post('post/save/delete/{id}',[SaveControllers::class,'delete_save']);


    Route::get('profile/story/index',[StoriesController::class,'index']);
    Route::get('profile/story/watch/{id}/views',[StoriesController::class,'getViews']);
    Route::get('profile/Mystory',[StoriesController::class,'getMyStory']);
    Route::post('profile/story',[StoriesController::class,'create']);
    Route::post('profile/story/watch/{id}',[StoriesController::class,'view_story']);
    Route::post('profile/story/upload_image',[StoriesController::class,'uploadStoryImage']);
    Route::post('profile/story/{id}',[StoriesController::class,'delete']);


    Route::post('post/report/{id}',[ReportController::class,'makeReport']);
    
    Route::post('post/video',[VideoController::class,'create']);
    Route::get('post/video',[VideoController::class,'index']);
    
    Route::get('search/{text}',[SearchController::class,'search']);

        Route::middleware('admin')->group(function(){
            Route::get('getInfo',[AdminController::class,'index']);
            Route::get('getAllPosts',[AdminController::class,'getposts']);
            Route::get('getfilterposts/{post_type_id}',[AdminController::class,'getfilterposts']);
            Route::get('getAllUser',[AdminController::class,'getAllUser']);
            Route::post('deleteUser/{id}',[AdminController::class,'deleteUser']);
            Route::post('AuthUser/{id}',[AdminController::class,'AuthUser']);
            Route::get('getUserPost/{id}',[AdminController::class,'getUserPost']);

});
});



