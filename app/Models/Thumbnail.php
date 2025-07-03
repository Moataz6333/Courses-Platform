<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    protected $fillable=[
        'public_id',
        'resource_type',
        'path',
        'lesson_id',
        'course_id',
    ];
    
}
