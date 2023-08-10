<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Report;

use Illuminate\Http\Request;

class ReportController extends Controller
{



    public function makeReport($id){
        $repo= Report::where([
            'post_id'=>$id,
            'user_id'=>auth()->user()->id
        ])->first();

        if($repo){
            return response()->json([
                'message'=>'cant do more than one report',
            ]);
        }

        $report = Report::create([
            'user_id'=>auth()->user()->id,
            'post_id'=>$id,
        ]);
        
        return response()->json([
            'data'=>$report
        ]);
    }


    public function handleReport(){
        $posts = Post::all();

        foreach($posts as $post){
            $reports = Report::where('post_id','=',$post['id'])->count();
            // $report_num = 
            if($reports >= 30){
                $post->delete();
            }
        }

        return response()->json([
            'message' => 'done',
        ]);
    }
}
