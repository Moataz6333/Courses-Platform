<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    
    protected $fillable=[
        'exam_id',
        'head',
        'correct_ans',
        'points',
        'has_options',
       
    ];
    public function options() {
        return $this->hasMany(Option::class);
    }
    public function exam()  {
        return $this->belongsTo(Exam::class);
    }
    public function media() {
        return $this->hasOne(Media::class);
    }
}
