<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    // ─── Email Verification ─────────────────────────────────────────────────

    public function showVerifyForm()
    {
        if (!session('pending_verify_email')) {
            return redirect()->route('auth.register');
        }
        return view('auth.otp-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], ['otp.required' => 'Kode OTP wajib diisi.', 'otp.size' => 'Kode OTP harus 6 digit.']);

        $email = session('pending_verify_email');
        if (!$email) {
            return redirect()->route('auth.register')->with('error', 'Sesi expired. Silakan daftar ulang.');
        }

        $otpRecord = OtpCode::where('email', $email)
            ->where('code', $request->otp)
            ->where('type', 'verify')
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        // Mark OTP used
        $otpRecord->update(['is_used' => true]);

        // Verify user
        $user = User::where('email', $email)->firstOrFail();
        $user->update(['email_verified_at' => now()]);

        // Clear session
        session()->forget('pending_verify_email');

        // Auto-login
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', "🎉 Email berhasil diverifikasi! Selamat datang, {$user->name}!");
    }

    public function resendVerify(Request $request)
    {
        $email = session('pending_verify_email');
        if (!$email) {
            return redirect()->route('auth.register');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('auth.register');
        }

        $otp = OtpCode::generate($email, 'verify');
        try {
            Mail::to($email)->send(new OtpVerificationMail($otp->code, $user->name, 'verify'));
        } catch (\Exception $e) {}

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda!');
    }

    // ─── Password Reset OTP ──────────────────────────────────────────────────

    public function showForgotForm()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.exists'   => 'Email tidak ditemukan dalam sistem kami.',
        ]);

        $user = User::where('email', $request->email)->first();
        $otp  = OtpCode::generate($request->email, 'reset');
        try {
            Mail::to($request->email)->send(new OtpVerificationMail($otp->code, $user->name, 'reset'));
        } catch (\Exception $e) {}

        session(['pending_reset_email' => $request->email]);

        return redirect()->route('auth.otp.reset-form')
            ->with('success', "Kode OTP reset password dikirim ke {$request->email}. Berlaku 10 menit.");
    }

    public function showResetOtpForm()
    {
        if (!session('pending_reset_email')) {
            return redirect()->route('auth.forgot');
        }
        return view('auth.otp-reset');
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'otp'                  => 'required|string|size:6',
            'password'             => 'required|string|min:8|confirmed',
        ], [
            'otp.required'          => 'Kode OTP wajib diisi.',
            'otp.size'              => 'Kode OTP harus 6 digit.',
            'password.required'     => 'Password baru wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        $email = session('pending_reset_email');
        if (!$email) {
            return redirect()->route('auth.forgot')->with('error', 'Sesi expired. Silakan ulangi.');
        }

        $otpRecord = OtpCode::where('email', $email)
            ->where('code', $request->otp)
            ->where('type', 'reset')
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        $otpRecord->update(['is_used' => true]);

        $user = User::where('email', $email)->firstOrFail();
        $user->update(['password' => bcrypt($request->password)]);

        session()->forget('pending_reset_email');

        return redirect()->route('auth.login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}
