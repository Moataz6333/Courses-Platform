<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tracks = [
            ['name' => 'Programming'],
            ['name' => 'Web Development'],
            ['name' => 'Mobile Development'],
            ['name' => 'Game Development'],
            ['name' => 'Data Science'],
            ['name' => 'Artificial Intelligence'],
            ['name' => 'Machine Learning'],
            ['name' => 'Cybersecurity'],
            ['name' => 'Computer Science'],
            ['name' => 'Engineering'],
            ['name' => 'Electrical Engineering'],
            ['name' => 'Mechanical Engineering'],
            ['name' => 'Civil Engineering'],
            ['name' => 'Software Engineering'],
            ['name' => 'Cloud Computing'],
            ['name' => 'DevOps'],
            ['name' => 'Database Administration'],
            ['name' => 'UI/UX Design'],
            ['name' => 'Graphic Design'],
            ['name' => 'Digital Marketing'],
            ['name' => 'SEO'],
            ['name' => 'Business'],
            ['name' => 'Entrepreneurship'],
            ['name' => 'Finance'],
            ['name' => 'Accounting'],
            ['name' => 'Economics'],
            ['name' => 'Mathematics'],
            ['name' => 'Statistics'],
            ['name' => 'Physics'],
            ['name' => 'Chemistry'],
            ['name' => 'Biology'],
            ['name' => 'Environmental Science'],
            ['name' => 'Psychology'],
            ['name' => 'Sociology'],
            ['name' => 'Philosophy'],
            ['name' => 'History'],
            ['name' => 'Law'],
            ['name' => 'Political Science'],
            ['name' => 'Languages'],
            ['name' => 'English'],
            ['name' => 'French'],
            ['name' => 'Spanish'],
            ['name' => 'Arabic'],
            ['name' => 'Chinese'],
            ['name' => 'Project Management'],
            ['name' => 'Human Resources'],
            ['name' => 'Education'],
            ['name' => 'Healthcare'],
        ];

        DB::table('tracks')->insert($tracks);
    }
}
