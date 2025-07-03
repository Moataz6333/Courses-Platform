<?php
namespace App\Service;

use App\Http\Resources\CourseResource;
use App\Models\Teacher;

class TeacherService 
{
    public $teacher;
    public function __construct(Teacher $teacher) {
        $this->teacher=$teacher;
    }

    public function socialMedia(){
        $links=$this->teacher->socialmedia;
        return [
            'facebook'=>$links->where('name','facebook')->first()->link ?? '',
            'instagram'=>$links->where('name','instagram')->first()->link ?? '',
            'whatsapp'=>$links->where('name','whatsapp')->first()->link ?? '',
        ];
    }
    public function courses() {
        return CourseResource::collection($this->teacher->courses);
    }
    
}

?>