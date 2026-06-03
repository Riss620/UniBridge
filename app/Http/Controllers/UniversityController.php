<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function dashboard()
    {
        $university = auth()->user()->university;
        $stats = [
            'total'     => $university ? $university->students()->count() : 0,
            'active'    => $university ? $university->students()->where('status', 'active')->count() : 0,
            'graduated' => $university ? $university->students()->where('status', 'graduated')->count() : 0,
        ];
        $recentStudents = $university ? $university->students()->latest()->take(5)->get() : collect();
        return view('university.dashboard', compact('university', 'stats', 'recentStudents'));
    }

    public function students(Request $request)
    {
        $university = auth()->user()->university;
        if (!$university) {
            return redirect()->route('university.dashboard')->with('error', 'University profile not found.');
        }
        if ($university->status !== 'approved') {
            return redirect()->route('university.dashboard')->with('warning', 'Your university must be approved to manage students.');
        }
        $search = $request->get('search');
        $query = $university->students()->latest();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%");
            });
        }
        $students = $query->paginate(15);
        return view('university.students', compact('students', 'search', 'university'));
    }

    public function createStudent()
    {
        $university = auth()->user()->university;
        if (!$university || $university->status !== 'approved') {
            return redirect()->route('university.dashboard')->with('warning', 'Approval required to add students.');
        }
        return view('university.student-form', ['student' => null, 'university' => $university]);
    }

    public function storeStudent(Request $request)
    {
        $university = auth()->user()->university;
        if (!$university || $university->status !== 'approved') {
            abort(403);
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'roll_no'        => 'required|string|unique:students,roll_no|max:50',
            'email'          => 'nullable|email|max:255',
            'course'         => 'required|string|max:100',
            'department'     => 'required|string|max:100',
            'year'           => 'required|integer|min:1|max:7',
            'cgpa'           => 'nullable|numeric|min:0|max:10',
            'status'         => 'required|in:pending,active,graduated,dropout',
            'admission_year' => 'required|digits:4|integer',
            'passout_year'   => 'nullable|digits:4|integer',
            'gender'         => 'nullable|in:male,female,other',
            'state_of_origin'=> 'nullable|string|max:100',
        ]);

        $university->students()->create($validated);
        return redirect()->route('university.students')->with('success', 'Student record added successfully.');
    }

    public function editStudent(Student $student)
    {
        $university = auth()->user()->university;
        if (!$university || $university->status !== 'approved') abort(403);
        if ($student->university_id !== $university->id) abort(403);
        return view('university.student-form', compact('student', 'university'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $university = auth()->user()->university;
        if (!$university || $university->status !== 'approved') abort(403);
        if ($student->university_id !== $university->id) abort(403);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'roll_no'        => 'required|string|max:50|unique:students,roll_no,'.$student->id,
            'email'          => 'nullable|email|max:255',
            'course'         => 'required|string|max:100',
            'department'     => 'required|string|max:100',
            'year'           => 'required|integer|min:1|max:7',
            'cgpa'           => 'nullable|numeric|min:0|max:10',
            'status'         => 'required|in:pending,active,graduated,dropout',
            'admission_year' => 'required|digits:4|integer',
            'passout_year'   => 'nullable|digits:4|integer',
            'gender'         => 'nullable|in:male,female,other',
            'state_of_origin'=> 'nullable|string|max:100',
        ]);

        $student->update($validated);
        return redirect()->route('university.students')->with('success', 'Student record updated.');
    }

    public function deleteStudent(Student $student)
    {
        $university = auth()->user()->university;
        if (!$university || $university->status !== 'approved') abort(403);
        if ($student->university_id !== $university->id) abort(403);
        $student->delete();
        return back()->with('success', 'Student record deleted.');
    }

    public function profile()
    {
        $university = auth()->user()->university;
        return view('university.profile', compact('university'));
    }

    public function approveStudent(Student $student)
    {
        $university = auth()->user()->university;
        if (!$university || $university->status !== 'approved') abort(403);
        if ($student->university_id !== $university->id) abort(403);

        $student->update(['status' => 'active']);

        return back()->with('success', "Student '{$student->name}' has been approved.");
    }
}
