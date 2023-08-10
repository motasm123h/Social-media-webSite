<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;
    protected $table='comments';
    protected $fillable=['comment','user_id','post_id'];

    public function user()
    {
        return $this->belongsTo(User::class);        
    }   
}
