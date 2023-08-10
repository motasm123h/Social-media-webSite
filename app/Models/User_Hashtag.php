<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Hashtag extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','hashtag_id'];

    
}
