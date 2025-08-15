<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $users=[
        //     [
        //         'name'=>'Pr Moataz Ahmed Ahmed',
        //         'email'=>'admin@admin.com',
        //         'password'=>Hash::make('password'),
        //         'birthdate'=>date_create('16-6-2003'),
        //         'gender'=>'male',
        //         'role'=>'teacher',
        //     ],
        //     [
        //         'name'=>'student one',
        //         'email'=>'student@student.com',
        //         'password'=>Hash::make('password'),
        //         'birthdate'=>date_create('16-6-2003'),
        //         'gender'=>'male',
        //         'role'=>'student',
        //     ],
        // ];
        // User::factory()->count(50)->create()->each(function ($user){
        //     $user->student()->create(
        //         Student::factory()->make()->toArray()
        //     );
        //     $user->enrollments()->create([
        //          'course_id' => 1,
        //         'courseType' => 'free',
        //         'paid'=>true,
        //     ]);
        // });
        // User::factory()->count(30)->create()->each(function ($user){
        //     $user->teacher()->create(
        //         Teacher::factory()->make()->toArray()
        //     );
        // });
        $users=User::all();
        foreach ($users as $user) {
            $user->birthdate=fake()->dateTimeBetween('-30 years','-14 years');
            $user->save();
        }

    }
}
