<?php

namespace App\Http\Controllers;

use App\Models\GovernmentUser;
use App\Models\Student;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_universities' => University::count(),
            'pending'            => University::where('status', 'pending')->count(),
            'approved'           => University::where('status', 'approved')->count(),
            'rejected'           => University::where('status', 'rejected')->count(),
            'total_students'     => Student::count(),
            'total_govt'         => GovernmentUser::count(),
            'total_users'        => User::count(),
        ];
        $recentUniversities = University::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentUniversities'));
    }

    public function universities(Request $request)
    {
        $filter = $request->get('status', 'all');
        $query = University::with('user')->latest();
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }
        $universities = $query->paginate(15);
        return view('admin.universities', compact('universities', 'filter'));
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

    public function students(Request $request)
    {
        $search = $request->get('search');
        $query = Student::with('university')->latest();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%");
            });
        }
        $students = $query->paginate(20);
        return view('admin.students', compact('students', 'search'));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User has been {$status}.");
    }
}
