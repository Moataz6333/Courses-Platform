<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable=[
        'course_id',
        'value',
        'code',
        'end_at',
        'isPublic',
    ];
    
}
