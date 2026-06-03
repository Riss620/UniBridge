<?php

namespace App\Http\Controllers;

use App\Models\GovernmentUser;
use App\Models\Student;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function createUserForm()
    {
        $approvedUniversities = University::where('status', 'approved')->orderBy('name')->get();
        return view('admin.create-user', compact('approvedUniversities'));
    }

    public function storeUser(Request $request)
    {
        $role = $request->input('role');
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,university,government,student',
        ];

        if ($role === 'university') {
            $rules += [
                'university_name'    => 'required|string|max:255',
                'affiliation_no'     => 'required|string|unique:universities,affiliation_no',
                'city'               => 'required|string|max:100',
                'state'              => 'required|string|max:100',
                'contact_phone'      => 'required|string|max:15',
                'university_type'    => 'required|in:central,state,deemed,private',
                'university_address' => 'required|string|max:500',
            ];
        } elseif ($role === 'government') {
            $rules += [
                'department'  => 'required|string|max:255',
                'designation' => 'required|string|max:255',
            ];
        } elseif ($role === 'student') {
            $rules += [
                'university_id'  => 'required|exists:universities,id',
                'roll_no'        => 'required|string|max:50|unique:students,roll_no',
                'course'         => 'required|string|max:100',
                'department'     => 'required|string|max:100',
                'year'           => 'required|integer|min:1|max:7',
                'admission_year' => 'required|digits:4|integer',
                'gender'         => 'nullable|in:male,female,other',
                'state_of_origin'=> 'nullable|string|max:100',
            ];
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => $role,
            'email_verified_at' => now(),
        ]);

        if ($role === 'university') {
            University::create([
                'user_id'        => $user->id,
                'name'           => $validated['university_name'],
                'address'        => $validated['university_address'],
                'city'           => $validated['city'],
                'state'          => $validated['state'],
                'affiliation_no' => $validated['affiliation_no'],
                'contact_phone'  => $validated['contact_phone'],
                'type'           => $validated['university_type'],
                'status'         => 'approved', // Admin-created is approved immediately
            ]);
        } elseif ($role === 'government') {
            GovernmentUser::create([
                'user_id'     => $user->id,
                'department'  => $validated['department'],
                'designation' => $validated['designation'],
            ]);
        } elseif ($role === 'student') {
            Student::create([
                'university_id'  => $validated['university_id'],
                'name'           => $validated['name'],
                'roll_no'        => $validated['roll_no'],
                'email'          => $validated['email'],
                'course'         => $validated['course'],
                'department'     => $validated['department'],
                'year'           => $validated['year'],
                'admission_year' => $validated['admission_year'],
                'gender'         => $validated['gender'] ?? null,
                'state_of_origin'=> $validated['state_of_origin'] ?? null,
                'status'         => 'active', // Admin-created is active immediately
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        if ($user->role === 'student') {
            Student::where('email', $user->email)->delete();
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
