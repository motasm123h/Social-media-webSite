<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\User;
use App\Models\story_views;


class StoriesController extends Controller
{
     public function index(Request $request)
    {
        $Friends_id = auth()->user()->getFriendsIds();
        $stories = Story::with([
            'user' => function($q){
            $q->select('id','name','profile_image');
                },  
        ])
            ->where('created_at', '>=', now()->subDay())
            ->whereIn('user_id',$Friends_id)
            ->withCount('views')
            ->get();
            
        return response()->json([
            'stories'=>$stories
            ]);
    }

    public function getMyStory(){
        $stories = Story::with([
            'user' => function($q){
            $q->select('id','name','profile_image');
                },  
        ])
            ->where('created_at', '>=', now()->subDay())
            ->where('user_id',auth()->user()->id)
            ->withCount('views')
            ->get();
            
        return response()->json([
            'stories'=>$stories
            ]);
    }

    public function getViews($id) {

        $story=Story::where('id',$id)->first();
        $story_views = $story->views()->pluck('user_id');
        $views = User::select('id','name','profile_image')->
        whereIn('id',$story_views)
        ->get();
        
        return response()->json([
            'views' => $views,
        ]) ;
    }

    public function uploadStoryImage (Request $request){
        $atter = $request->validate([
            'image' => ['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
            'name' =>  ['required','string']
        ]);

        $image_path = $request->file('image')->move(public_path('stories/image'),$request->input('name'));

        return response()->json([
            'message' =>'image upload successfully',
        ]);
    }

    public function create(Request $request){
        $atter = $request->validate([
            'content' => ['required','string'],
            //image or video
            'media_type' => ['required','string'],
            //name
            'media_url' => ['required','string']
        ]);


        $story = Story::create([
            'user_id' => auth()->user()->id,
            'media_type' => $atter['media_type'],
            'media_url' => $atter['media_url'],
            'content' => $atter['content'],
        ]);

        $story_back = Story::with(['user' => function($q){
            $q->select('id','name','profile_image');
        }])->where('id','=',$story['id'])
        ->get();


        return response()->json([
            'message' => $story_back,
        ]);
    }



    public function delete($id){
        $story = Story::where('id',$id)->first();

        if(!$story){
            return response()->json([
                'message' => 'the result is not found !' 
            ]);
            
            
        }

            return response()->json([
                'message' => $story->delete(), 
            ]);
    }



    public function view_story($id){
        
        $view = story_views::create([
            'user_id' => auth()->user()->id,
            'story_id' => $id,
        ]);

        return response()->json([
            'message' => true
        ]);
    }
}
