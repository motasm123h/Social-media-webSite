<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_HashTag;
use App\Models\Hashtag;


class AuthController extends Controller
{
    function register(Request $req){
        // dd($req->file('image'));
        $atter = $req ->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','unique:'.User::class],
            'password' => ['required','min:8'],
        ]);
       
        $user = User::create([
            'name' => $atter['name'],
            'email' => $atter['email'],
            'password' => Hash::make($atter['password']),
        ]);

        if($user){
            return response([
                'user' => User::where('id','=',$user->id)->first(),
                'token' => $user->createToken('secret')->plainTextToken ,
            ],200);
        }
        else{
            return response([
                'message' => "sorry you can't register right now | Please tye leater"
            ]);
        }

    }
    
    public function login(Request $request)
    {
        $atter = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!Auth::attempt($atter))
        {
            return response([
                'message' => 'Inavild Crdenatail'
            ],403);            
        }
        auth()->user()->update(
            ['online' => 1]
        );

        return response([
            'user' => auth()->user(),
            'token' =>auth()->user()->createToken('secret')->plainTextToken
        ],200);
    }
    //true
    public function HashTag(Request $request){
        $input=$request->all();
        $types=$input['type'];

        foreach($types as $type){
            
            User_HashTag::create([
                'user_id' => auth()->user()->id,
                'hashtag_id' => $type,
            ]);
        }
        return response()->json([
            "message" => "done"
        ]);
    }
    //true
    public function Type(Request $request){
        Hashtag::create(['type'=>$request->type]);
    }

    public function uploadImage(Request $request){
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


    public function deleteAccuont(){
        return response()->json([
            'message' => auth()->user()->delete(),
        ]);
    }


    public function UpdateInfo(Request $request){
        $atter = $request->validate([
            'name'=>['required'],
            'profile_image' =>['required'],
            'cover_image' =>['required'],
            'address' =>['string','required'],
        ]);
        $user = auth()->user();
        $user->update([
            'name'=>$atter['name'],
            'profile_image' => $atter['profile_image'],
            'cover_image' => $atter['cover_image'],
            'address' => $atter['address'],
        ]);

        return response()->json([
            'user' => $user
            ]);
    }

    function logout(){
        auth()->user()->update([
            'online'=>0
            ]);
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'logout success'
        ],200);
    }
}
