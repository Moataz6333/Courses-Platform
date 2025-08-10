<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable=[
        'user_id',
        'question_id',
        'answer',
        'result_id',
        'corrected_by',
        'feedback',
        'ai_grade',
    ];
    protected  $with=['question'];
    public function question() {
        return $this->belongsTo(Question::class);
    }
}
