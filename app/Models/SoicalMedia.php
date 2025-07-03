<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoicalMedia extends Model
{
    protected $fillable=[
        'name',
        'link',
        'teacher_id'
    ];
}
