<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\University;
use Illuminate\Http\Request;

class GovernmentController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_universities' => University::where('status', 'approved')->count(),
            'total_students'     => Student::whereHas('university', fn($q) => $q->where('status', 'approved'))->count(),
            'states'             => University::where('status', 'approved')->distinct('state')->count('state'),
        ];
        $universities = University::where('status', 'approved')->latest()->take(6)->get();
        return view('government.dashboard', compact('stats', 'universities'));
    }

    public function data(Request $request)
    {
        $search     = $request->get('search');
        $state      = $request->get('state');
        $course     = $request->get('course');
        $status     = $request->get('status');
        $university = $request->get('university');

        $query = Student::with('university')
            ->whereHas('university', fn($q) => $q->where('status', 'approved'));

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%");
            });
        }
        if ($state)      $query->whereHas('university', fn($q) => $q->where('state', $state));
        if ($course)     $query->where('course', 'like', "%{$course}%");
        if ($status)     $query->where('status', $status);
        if ($university) $query->where('university_id', $university);

        $students     = $query->paginate(20)->withQueryString();
        $states       = University::where('status', 'approved')->distinct()->pluck('state')->sort();
        $universities = University::where('status', 'approved')->get();

        return view('government.data', compact('students', 'search', 'state', 'course', 'status', 'university', 'states', 'universities'));
    }

    public function export(Request $request)
    {
        $query = Student::with('university')
            ->whereHas('university', fn($q) => $q->where('status', 'approved'));

        if ($request->state)      $query->whereHas('university', fn($q) => $q->where('state', $request->state));
        if ($request->course)     $query->where('course', 'like', "%{$request->course}%");
        if ($request->status)     $query->where('status', $request->status);

        $students = $query->get();

        $csvContent = "Name,Roll No,Course,Department,Year,CGPA,Status,University,State\n";
        foreach ($students as $s) {
            $csvContent .= implode(',', [
                "\"{$s->name}\"", $s->roll_no, "\"{$s->course}\"",
                "\"{$s->department}\"", $s->year, $s->cgpa ?? '',
                $s->status, "\"{$s->university->name}\"", $s->university->state,
            ]) . "\n";
        }

        return response($csvContent, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="unibridge_open_data_' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    public function universities(Request $request)
    {
        $filter = $request->get('status', 'all');
        $query = University::with('user')->latest();
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }
        $universities = $query->paginate(15);
        return view('government.universities', compact('universities', 'filter'));
    }

    public function approve(University $university)
    {
        $university->update(['status' => 'approved', 'rejection_reason' => null]);
        return back()->with('success', "University '{$university->name}' has been approved.");
    }

    public function reject(Request $request, University $university)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);
        $university->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason ?? 'Does not meet registration criteria.',
        ]);
        return back()->with('success', "University '{$university->name}' has been rejected.");
    }
}
