<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'answer',
        'status',
        'is_repeat',
        'level_id',
        'master_id'
    ];  
   
    public function subQuestionList($id)
    {
        return subQuestion::where('master_id',$id)->where('level_id', '!=', 1)->get();
    }

}
