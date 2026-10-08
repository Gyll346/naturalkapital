<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $throttleKey = 'login_device|'.$request->ip();
        $lockoutSeconds = 0;

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $lockoutSeconds = RateLimiter::availableIn($throttleKey);
        }

        return view('auth.login', [
            'lockout_seconds' => $lockoutSeconds,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Kunci blokir berbasis IP perangkat
        $throttleKey = 'login_device|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            ActivityLog::create([
                'user_id' => null,
                'action' => 'LOGIN_BLOCKED',
                'module' => 'AUTH',
                'details' => "Perangkat diblokir karena 3 kali gagal login. Sisa waktu tunggu: {$seconds} detik.",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->with('lockout_seconds', $seconds)->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            // Catat log aktivitas login
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'LOGIN',
                'module' => 'AUTH',
                'details' => 'Admin berhasil login ke panel kontrol.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey, 300);

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('lockout_seconds', $seconds)->onlyInput('email');
        }

        $attemptsLeft = RateLimiter::retriesLeft($throttleKey, 3);
        $errorMessage = "Email atau kata sandi salah. Sisa percobaan perangkat ini: {$attemptsLeft} kali sebelum diblokir 5 menit.";

        return back()->withErrors([
            'email' => $errorMessage,
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'LOGOUT',
                'module' => 'AUTH',
                'details' => 'Admin keluar dari sistem.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
