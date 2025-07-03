<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;
use App\Models\SoicalMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'description',
        'national_id',
        'phone',
        'specialization',
    ];
    // protected $with = ['user','courses','socialmedia'];
    public function user()  {
        return $this->belongsTo(User::class);
    }
    public function courses()  {
        return $this->hasMany(Course::class);
    }
    public function socialmedia()  {
        return $this->hasMany(SoicalMedia::class);
    }
}
