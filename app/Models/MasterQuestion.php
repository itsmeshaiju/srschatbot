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
        'is_first_question',
        'is_last_question',

    ];

    public function subQuestion()
    {
        return $this->hasMany(subQuestion::class,'main_question_id');
    }

    public static function updateAllRows($newValues)
    {
        // Use the update method to update all rows with new values
        return self::query()->update($newValues);
    }
}
