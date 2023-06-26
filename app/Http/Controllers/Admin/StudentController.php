<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentFormRequest;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Models\Admin\Student;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.settings.students.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = Classroom::all();
        return view('admin.settings.students.create', compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentFormRequest $request)
    {
        $validatedData = $request->validated();
        $classroom = Classroom::findOrFail($validatedData['classroom_id']);
        $student = $classroom->student()->create([
            'classroom_id' => $validatedData['classroom_id'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'status' => $request === true ? '1' : '0',
        ]);

        return redirect('admin/settings/students')->with('message', 'Student Added Successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
