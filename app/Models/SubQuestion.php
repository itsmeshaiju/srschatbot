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
        'next_question_id',
        'status',
        'main_question_id',
        'is_repeat',
    ];  
   
    public function mainQuestion()
    {
        return $this->belongsTo(MasterQuestion::class,'main_question_id');
    }

}
