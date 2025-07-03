<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable=[
        'user_id',
        'course_id',
        'courseType',
        'paid',
        'uuid',
    ];
    public function user()  {
        return $this->belongsTo(User::class);
    }
    public function course()  {
        return $this->belongsTo(Course::class);
    }
    public function transaction() {
        return $this->hasOne(Transaction::class,'enrollment_id');
    }
}
