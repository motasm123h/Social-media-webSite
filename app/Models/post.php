<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Save;

class post extends Model
{
    use HasFactory;
    protected $table ='posts';
    protected $fillable =['user_id','text','image','hashtag_id'];

  
    public function user()
    {
        return $this->belongsTo(User::class);        
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function like()
    {
        return $this->morphMany(Like::class, 'likeable', 'likeable_type', 'likeable_id');
    }

public function likes()
{
    return $this->morphMany(Like::class, 'likeable');
}
   public function saves()
    {
        return $this->hasMany(Save::class);
    }
}
