<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable=[
        'user_id',
        'exam_id',
        'total',
        'correct',
        'hasWritten',
        'status',
        'timeTaken',
        'grade',
    ];
    public function exam() {
        return $this->belongsTo(Exam::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function answers()  {
        return $this->hasMany(Answer::class);
    }
}
