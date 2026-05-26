<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        $user = auth()->user();

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account has been deactivated.']);
        }

        $request->session()->regenerate();

        if (!$user->isVerified()) {
            return redirect()->route('otp.verify');
        }

        return redirect()->intended($this->dashboardRoute($user->role));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    private function dashboardRoute(string $role): string
    {
        return match ($role) {
            'admin'       => route('admin.dashboard'),
            'university'  => route('university.dashboard'),
            'government'  => route('government.dashboard'),
            'student'     => route('student.dashboard'),
            default       => route('home'),
        };
    }
}
