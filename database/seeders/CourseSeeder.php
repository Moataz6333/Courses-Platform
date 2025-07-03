<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\Track;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trackKeywords = [
            'Programming' => 'code coding algorithms programming languages python java c++ problem-solving',
            'Web Development' => 'front-end backend full-stack react angular laravel html css javascript vuejs php',
            'Mobile Development' => 'android ios flutter react-native kotlin swift mobile apps',
            'Game Development' => 'unity unreal c# game-design 3d 2d gamedev graphics',
            'Data Science' => 'data-analysis pandas numpy data-cleaning visualization python r',
            'Artificial Intelligence' => 'ai neural-networks deep-learning ml models nlp computer-vision',
            'Machine Learning' => 'regression classification scikit-learn supervised unsupervised clustering tensorflow',
            'Cybersecurity' => 'security hacking penetration-testing firewalls encryption ethical-hacking malware',
            'Computer Science' => 'os computer-architecture data-structures algorithms theory computation',
            'Engineering' => 'design mechanics systems control thermodynamics circuits',
            'Electrical Engineering' => 'circuits signals electronics voltage current microcontrollers',
            'Mechanical Engineering' => 'machines dynamics fluid-mechanics cad solidworks',
            'Civil Engineering' => 'construction structural design autocad surveying',
            'Software Engineering' => 'software-development agile scrum testing version-control',
            'Cloud Computing' => 'aws azure gcp cloud-deployment serverless kubernetes docker',
            'DevOps' => 'ci/cd automation jenkins ansible monitoring infrastructure',
            'Database Administration' => 'mysql postgresql nosql mongodb sql optimization',
            'UI/UX Design' => 'user-experience wireframes figma adobe-xd user-interface prototyping',
            'Graphic Design' => 'photoshop illustrator logo typography branding',
            'Digital Marketing' => 'seo sem social-media ads google-analytics content-marketing',
            'SEO' => 'search-engine-optimization keywords backlinks rankings meta-tags',
            'Business' => 'management strategy startup operations sales finance',
            'Entrepreneurship' => 'startup business-model pitch fundraising innovation',
            'Finance' => 'investing budgeting accounting stocks portfolio',
            'Accounting' => 'bookkeeping tax-audit financial-statements reports',
            'Economics' => 'microeconomics macroeconomics supply-demand inflation trade',
            'Mathematics' => 'algebra calculus geometry statistics trigonometry math',
            'Statistics' => 'probability distributions mean variance hypothesis-testing',
            'Physics' => 'mechanics optics waves thermodynamics electromagnetism',
            'Chemistry' => 'organic inorganic reactions periodic-table lab',
            'Biology' => 'cells dna genetics anatomy ecology evolution',
            'Environmental Science' => 'climate-change pollution sustainability ecology conservation',
            'Psychology' => 'cognition behavior therapy brain personality mental-health',
            'Sociology' => 'society culture norms identity research',
            'Philosophy' => 'ethics logic metaphysics reasoning existentialism',
            'History' => 'ancient modern wars revolutions empires',
            'Law' => 'legal contract justice criminal civil court',
            'Political Science' => 'government politics democracy policy international-relations',
            'Languages' => 'grammar vocabulary speaking writing listening pronunciation',
            'English' => 'grammar reading speaking writing ielts toefl',
            'French' => 'vocab grammar pronunciation speaking writing',
            'Spanish' => 'conversation grammar verbs speaking beginner',
            'Arabic' => 'reading writing grammar fusha dialect',
            'Chinese' => 'mandarin tones characters speaking listening',
            'Project Management' => 'pmp planning scheduling risk agile waterfall kanban',
            'Human Resources' => 'recruitment hiring training payroll hr policies',
            'Education' => 'teaching learning curriculum classroom pedagogy',
            'Healthcare' => 'medicine nursing first-aid health-safety patient-care',
        ];
        $teachers = Teacher::all()->except(1)->load('user');
        foreach ($teachers as $teacher) {
            DB::table('courses')->insert([
                [
                    'teacher_id'=>$teacher->id,
                    'title' => $teacher->specialization . ' Course 1 by ' . $teacher->user->name,
                    'description' => 'Lorem ipsum dolor sit amet.',
                    'price' => 0,
                    'track_id' => Track::where('name', $teacher->specialization)->first()->id,
                    'keyWords'=>$trackKeywords[$teacher->specialization],
                    'created_at'=>now()
                ],
                [
                    'teacher_id'=>$teacher->id,
                    'title' => $teacher->specialization . ' Course 2 by ' . $teacher->user->name,
                    'description' => 'Lorem ipsum dolor sit amet.',
                    'price' => 200,
                    'track_id' => Track::where('name', $teacher->specialization)->first()->id,
                    'keyWords'=>$trackKeywords[$teacher->specialization],
                    'created_at'=>now()
                ],]
                
            );
        }
    }
}
