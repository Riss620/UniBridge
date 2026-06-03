<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\GovernmentUser;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showForm(Request $request)
    {
        $role = $request->query('role', 'student');
        $approvedUniversities = University::where('status', 'approved')->orderBy('name')->get();
        return view('auth.register', compact('role', 'approvedUniversities'));
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'student');

        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => 'required|in:admin,university,government,student',
        ];

        if ($role === 'university') {
            $rules += [
                'university_name'    => 'required|string|max:255',
                'university_address' => 'required|string|max:500',
                'city'               => 'required|string|max:100',
                'state'              => 'required|string|max:100',
                'affiliation_no'     => 'required|string|unique:universities,affiliation_no',
                'contact_phone'      => 'required|string|max:15',
                'university_type'    => 'required|in:central,state,deemed,private',
            ];
        } elseif ($role === 'government') {
            $rules += [
                'department'   => 'required|string|max:255',
                'designation'  => 'required|string|max:255',
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

        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'role'           => $role,
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(10),
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
                'status'         => 'pending',
            ]);
        } elseif ($role === 'government') {
            GovernmentUser::create([
                'user_id'     => $user->id,
                'department'  => $validated['department'],
                'designation' => $validated['designation'],
            ]);
        } elseif ($role === 'student') {
            \App\Models\Student::create([
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
                'status'         => 'pending',
            ]);
        }

        // Send OTP via email
        Mail::to($user->email)->send(new SendOtpMail($user->name, $otp));

        Auth::login($user);

        return redirect()->route('otp.verify')->with('success', 'Registration successful! Please verify your email with the OTP sent.');
    }
}
