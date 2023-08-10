<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\story_views;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'media_type',
        'media_url',
    ] ;
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }


    public function views()
    {
        return $this->hasMany(story_views::class);
    }

    public function uniqueViews()
    {
        return $this->views()->distinct('user_id')->count('user_id');
    }

}
