<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'teacher_id',
        'title',
        'description',
        'price',
        'track_id',
        'keyWords',

    ];
    // protected $with=['teacher','lessons'];
    public function teacher()  {
        return $this->belongsTo(Teacher::class);
    }
    public function lessons()  {
        return $this->hasMany(Lesson::class);
    }
    public function exams() {
        return $this->hasMany(Exam::class);
    }
    public function thumbnail() {
        return $this->hasOne(Thumbnail::class);
    }
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
    public function track()  {
        return $this->belongsTo(Track::class);
    }
}
