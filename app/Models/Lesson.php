<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Media;
use App\Models\Course;

class Lesson extends Model
{
    protected $fillable=[
        'course_id',
        'title',
        'description'
    ];
    // protected $with=['media'];

    public function course()  {
        return $this->belongsTo(Course::class);
    }
    public function media()  {
        return $this->hasOne(Media::class);
    }
    public function thumbnail()  {
        return $this->hasOne(Thumbnail::class);
    }
}
