<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    //Relationship With Category
    public function category(){
        return $this->belongsTo(Category::class,'id');
    }
}
