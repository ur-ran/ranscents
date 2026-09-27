<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check email verification
            if (!$user->isVerified()) {
                Auth::logout();
                session(['pending_verify_email' => $request->email]);

                // Resend OTP
                $otp = \App\Models\OtpCode::generate($request->email, 'verify');
                try {
                    \Illuminate\Support\Facades\Mail::to($request->email)
                        ->send(new \App\Mail\OtpVerificationMail($otp->code, $user->name, 'verify'));
                } catch (\Exception $e) {}

                return redirect()->route('auth.otp.verify-form')
                    ->with('info', 'Email belum diverifikasi. Kode OTP baru telah dikirim.');
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        $name = Auth::user()?->name;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login')
            ->with('success', "Sampai jumpa, {$name}!");
    }
}
