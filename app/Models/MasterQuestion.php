<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'status',

    ];

    public function subQuestion()
    {
        return $this->hasMany(subQuestion::class,'main_question_id');
    }
}
