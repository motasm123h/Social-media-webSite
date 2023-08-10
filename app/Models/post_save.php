<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post_save extends Model
{
    protected $fillable = ['save_id','post_id'];
    use HasFactory;

}
