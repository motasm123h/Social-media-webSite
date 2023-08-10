<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freind extends Model
{
    use HasFactory;
    protected $table = 'freinds';
    protected $fillable = ['user_id' , 'freind_id' ,'status'] ;

    public function freind(){
        return $this->belongsTo(User::class,'freind_id');
    }

// public function friendRequestsSent()
// {
//     return $this->hasMany(Freind::class, 'user_id')->with('recipient');
// }

   public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
   
   
   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
