<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('student_code', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'student_code' => 'required|string|max:20|unique:students,student_code',
            'group'        => 'required|string|max:50',
            'mark'         => 'required|integer|min:0|max:100',
            'status'       => ['required', Rule::in(['Active', 'Pending', 'Inactive'])],
        ]);

        Student::create($data);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'student_code' => ['required', 'string', 'max:20',
                               Rule::unique('students', 'student_code')->ignore($student->id)],
            'group'        => 'required|string|max:50',
            'mark'         => 'required|integer|min:0|max:100',
            'status'       => ['required', Rule::in(['Active', 'Pending', 'Inactive'])],
        ]);

        $student->update($data);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}