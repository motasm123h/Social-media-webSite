<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HashTag;

class HashTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HashTag::create([
            'type'=>'Sport'
        ]);
        HashTag::create([
            'type'=>'News'
        ]);
        HashTag::create([
            'type'=>'Food'
        ]);
        HashTag::create([
            'type'=>'Music'
        ]);
        
        HashTag::create([
            'type'=>'Dance'
        ]);
        
        HashTag::create([
            'type'=>'Memes'
        ]);
        
        HashTag::create([
            'type'=>'funny'
        ]);
        
        HashTag::create([
            'type'=>'Love'
        ]);
        
        HashTag::create([
            'type'=>'Happy'
        ]);
        HashTag::create([
            'type'=>'Fashion'
        ]);
        HashTag::create([
            'type'=>'Comedy'
        ]);
        HashTag::create([
            'type'=>'Prank'
        ]);
        HashTag::create([
            'type'=>'Friends'
        ]);
        HashTag::create([
            'type'=>'Cooking'
        ]);
        HashTag::create([
            'type'=>'Travel'
        ]);
        HashTag::create([
            'type'=>'Animals'
        ]);
        
    }
}
