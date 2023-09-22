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
   
    public function subQuestionList($level,$id)
    {
       
        // if($level == 1){
        //     return subQuestion::where('master_id',$id)->where('level_id', 1)->get();
        // }else{
            return subQuestion::where('master_id',$id)->where('level_id', '!=', 1)->get();
        // }
       
    }

}
