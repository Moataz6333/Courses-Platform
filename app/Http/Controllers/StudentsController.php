<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\ClassesResource;
use App\Http\Resources\StudentResource;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search=$request->search ?? '';
        // create inital query
        $studentQuery=Student::query();
        // apply this query only when the search is sended
        $this->ApplyQuerey($studentQuery,$search);
        $students = StudentResource::collection($studentQuery->paginate(10));
        return Inertia('Students/Index', compact('students','search'));
    }
    protected function ApplyQuerey($query,$search)  {
        return $query->when($search,function($query,$search)  {
            $query->where('name','like','%'.$search.'%');
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $classes = ClassesResource::collection(Classes::all());
        return Inertia('Students/create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        Student::create($request->validated());
        return redirect()->route('students.index');
    }


    public function edit(string $id)
     {
        $student=StudentResource::make(Student::findOrFail($id));
        // $classes = ClassesResource::collection(Classes::all());
        return Inertia('Students/edit', compact('classes','student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());
        return redirect()->route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student=Student::findOrFail($id);
        $student->delete();
        return redirect()->route('students.index');
    }
}
