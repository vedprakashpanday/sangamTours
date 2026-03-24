<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // 1. Email mangwane wala form
    public function showLinkRequestForm() {
        return view('auth.admin_loginforgot-password');
    }

    // 2. Reset link bhejna
    public function sendResetLinkEmail(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // 3. Naya password set karne wala form
    public function showResetForm($token) {
        return view('auth.reset-password', ['token' => $token]);
    }

    // 4. Password update logic
    public function reset(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

       return $status === Password::PASSWORD_RESET
    ? redirect()->route('admin.login')->with('status', __($status)) // 'admin.login' karein
    : back()->withErrors(['email' => [__($status)]]);
    }
}