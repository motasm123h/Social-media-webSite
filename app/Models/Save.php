<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Save extends Model
{
    protected $fillable = ['user_id','post_id'];
    use HasFactory;
    // public function posts()
    //     {
    //         return $this->belongsToMany(Post::class, 'post_save');
    //     }
    
}
