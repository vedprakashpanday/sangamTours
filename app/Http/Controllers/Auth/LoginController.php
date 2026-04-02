<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;


class LoginController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return back()->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return back()->with('success', 'Account created successfully!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // 1. OTP Bhejo
public function sendOtp(Request $request) {
    $request->validate(['email' => 'required|email|exists:users,email']);
    $otp = rand(100000, 999999);
   
   Otp::where('created_at', '<', now()->subHours(24))->delete();
   
    Otp::updateOrCreate(
        ['email' => $request->email],
        ['otp' => Hash::make($otp), 'expires_at' => now()->addMinutes(10)]
    );

    Mail::to($request->email)->send(new OtpMail($otp));
    return response()->json(['status' => 'success', 'message' => 'OTP sent to your email.']);
}

// 2. OTP Verify karo
public function verifyOtp(Request $request) {
    $record = Otp::where('email', $request->email)->latest()->first();
    if($record && Hash::check($request->otp, $record->otp) && now()->lt($record->expires_at)) {
        return response()->json(['status' => 'success', 'redirect' => route('password.resetPage', ['email' => $request->email])]);
    }
    return response()->json(['status' => 'error', 'message' => 'Invalid or Expired OTP']);
}

// 3. Reset Password wala page dikhao
public function showResetPage($email) {
    return view('auth.user-reset-password', compact('email'));
}

// 4. Naya password update karo
public function updatePassword(Request $request) {
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Password update karein
    $user = \App\Models\User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // OTP record delete kar dein taaki dobara use na ho
    \App\Models\Otp::where('email', $request->email)->delete();

    // Direct login karwa dein ya login modal par bhej dein
    Auth::login($user);

    return redirect()->route('home')->with('success', 'Password updated successfully!');
}
}