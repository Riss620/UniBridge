<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function showForm()
    {
        return view('auth.otp-verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isVerified()) {
            return redirect()->intended(route('home'));
        }

        if ($user->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        if ($user->otp_expires_at < now()) {
            return back()->withErrors(['otp' => 'OTP has expired. Please resend.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'otp'               => null,
            'otp_expires_at'    => null,
        ]);

        $dashboardRoute = match ($user->role) {
            'admin'       => route('admin.dashboard'),
            'university'  => route('university.dashboard'),
            'government'  => route('government.dashboard'),
            'student'     => route('student.dashboard'),
            default       => route('home'),
        };

        return redirect($dashboardRoute)->with('success', 'Email verified! Welcome to UniBridge.');
    }

    public function resend()
    {/** @var User $user */
        
        $user = Auth::user();

        if (!$user || $user->isVerified()) {
            return redirect()->route('home');
        }

        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new SendOtpMail($user->name, $otp));

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}
