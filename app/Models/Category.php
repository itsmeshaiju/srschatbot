<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //Relationship With Question
    public function questions(){
        return $this->hasMany(Question::class,'cat_id');
    }
}
