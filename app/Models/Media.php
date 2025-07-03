<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable=[
        'type',
        'path',
        'lesson_id',
        'public_id',
        'resource_type',
         'question_id',
    ];
}
