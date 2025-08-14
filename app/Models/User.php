<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Teacher;
use App\Models\Student;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable,HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'birthdate',
        'google_id',
        'role',
        'profile',
        'cover_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

   
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function teacher() {
        return $this->hasOne(Teacher::class);
    }
    public function student()  {
        return $this->hasOne(Student::class);
    }
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
}
