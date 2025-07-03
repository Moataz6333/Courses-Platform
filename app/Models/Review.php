<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable=[
        'user_id',
        'course_id',
        'votes',
        'content',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
