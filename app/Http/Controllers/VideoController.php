<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index(){
        $video = Video::select('*')->withCount('like')
        ->with(['user' => function($query){
            $query->select('id','name','profile_image');
        }])->get();

        return response()->json([
            'message' =>$video,
        ]);
    }


public function create(Request $request){
    $attributes = $request->validate([
    'video' => 'required|mimetypes:video/mp4,video/webm,video/quicktime|max:204800', // 200 MB in bytes
    'text' => 'required',
]);

$video_name = Str::uuid().'.'.$attributes['video']->getClientOriginalExtension();
$video_path = $request->file('video')->move(public_path('video'), $video_name);
$video = Video::create([
    'user_id' => auth()->user()->id,
    'video' => $video_name,
    'text' => $attributes['text'],
]);

$back_video = Video::select('*')->withCount('like')
        ->with(['user' => function($query){
            $query->select('id','name','profile_image');
        }])->where('id', '=', $video->id)->first();
        


return response()->json([
    'message' => $back_video,
]);
    }
}
