<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable=[
        'course_id',
        'title',
        'period',
        'startDate',
        'uuid'
    ];

    public function questions()  {
        return $this->hasMany(Question::class);
    }
    public function course()  {
        return $this->belongsTo(Course::class);
    }
    public function results() {
        return $this->hasMany(Result::class);
    }
}
