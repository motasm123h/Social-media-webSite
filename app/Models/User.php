<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Post;
use App\Models\User;
use App\Models\Freind;
use DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'profile_image',
        'cover_image',
        'online',
        'Authentication_mark',
        'role',
        'address',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.User.'.$this->id;
    }

    public function likes()
{
    return $this->hasMany(Like::class);
}
    
    public function friendRequestsSent()
    {
        return $this->hasMany(Freind::class, 'user_id')
        ->where('status', 'pending')
        ->where('freind_id', '!=', auth()->user()->id)
        ->with(['freind']);
    }


    public function friendRequestsReceived()
    {
        return $this->hasMany(Freind::class, 'freind_id')
        ->where('status', 'pending')
        ->where('user_id', '!=', auth()->user()->id)
        ->with(['user']);
    }



    public function friendsID(){
        return $this->belongsToMany(User::class,'freinds','user_id','freind_id')->wherePivot('status','accpted')->withPivot('id')->withTimestamps();
    }

    public function friends(){
        return $this->belongsToMany(User::class,'freinds','user_id','freind_id')->wherePivot('status','accpted');
    }

    public function getFriendsId()
    {
        $friendIds = $this->friendsID()->with(['friendsID'])->get();
        return $friendIds;
    }


    public function getFriendsIds()
    {
        $friendIds = $this->friendsID()->pluck('freind_id');
        return $friendIds;
    }


    public function getFriends(){
        $friendIds = $this->getFriendsIds();
        return User::whereIn('id', $friendIds)->with(['freind'])->get();
    }

    public function Post(){
        return $this->hasMany(Post::class);
    }

}
