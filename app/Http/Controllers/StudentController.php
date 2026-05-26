<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        // Students don't have a direct profile table — their record is linked via roll_no/email
        // In a full implementation, universities link student users via email
        $studentRecord = \App\Models\Student::where('email', $user->email)->with('university')->first();
        return view('student.dashboard', compact('user', 'studentRecord'));
    }
}
