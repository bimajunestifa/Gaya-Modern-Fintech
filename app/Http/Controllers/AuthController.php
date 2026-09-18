<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\AuditLog;

class AuthController extends Controller
{
    /**
     * Show login form (accessible only by guests)
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle user login with Brute-Force Rate Limiting and Audit Trail
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        // Throttle key unique to email + IP address to prevent distributed brute force
        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        // Max 5 attempts per 60 seconds
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            AuditLog::create([
                'action' => 'LOGIN_RATE_LIMITED',
                'user_name' => $request->input('email'),
                'ip_address' => $request->ip(),
                'details' => "Percobaan login diblokir karena melebihi batas percobaan. Terkunci selama {$seconds} detik.",
            ]);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login yang gagal. Demi keamanan, akun terkunci sementara. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $remember)) {
            // Clear rate limiter on successful authentication
            RateLimiter::clear($throttleKey);

            // Regenerate session to prevent Session Fixation attacks
            $request->session()->regenerate();

            AuditLog::create([
                'action' => 'USER_LOGIN',
                'user_name' => Auth::user()->name,
                'ip_address' => $request->ip(),
                'details' => "Pengguna " . Auth::user()->name . " (" . Auth::user()->email . ") berhasil login ke sistem.",
            ]);

            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // Increment failed attempt counter (decay in 60 seconds)
        RateLimiter::hit($throttleKey, 60);

        AuditLog::create([
            'action' => 'USER_LOGIN_FAILED',
            'user_name' => $request->input('email'),
            'ip_address' => $request->ip(),
            'details' => "Percobaan login gagal untuk email: " . $request->input('email'),
        ]);

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah. Silakan periksa kembali.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle user registration with rate limiting and audit trail
     */
    public function register(Request $request)
    {
        $throttleKey = 'register|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak pendaftaran dari perangkat Anda. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }
        RateLimiter::hit($throttleKey, 300);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        AuditLog::create([
            'action' => 'USER_REGISTER',
            'user_name' => $user->name,
            'ip_address' => $request->ip(),
            'details' => "Pendaftaran akun baru {$user->email} berhasil.",
        ]);

        return redirect()->route('dashboard')->with('success', 'Akun perbankan Anda berhasil didaftarkan! Selamat datang, ' . $user->name);
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function forgotPassword(Request $request)
    {
        $throttleKey = 'forgot-password|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak permintaan reset kata sandi. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }
        RateLimiter::hit($throttleKey, 300);

        $request->validate(['email' => 'required|email']);

        AuditLog::create([
            'action' => 'PASSWORD_RESET_REQUEST',
            'user_name' => $request->input('email'),
            'ip_address' => $request->ip(),
            'details' => "Permintaan tautan reset kata sandi diajukan untuk {$request->input('email')}.",
        ]);

        return back()->with('success', 'Tautan instruksi reset kata sandi telah dikirimkan ke email Anda (Mode Simulasi Bank).');
    }

    /**
     * Handle logout with session invalidation & audit trail
     */
    public function logout(Request $request)
    {
        $userName = Auth::user() ? Auth::user()->name : 'User';
        
        AuditLog::create([
            'action' => 'USER_LOGOUT',
            'user_name' => $userName,
            'ip_address' => $request->ip(),
            'details' => "Pengguna {$userName} keluar dari sistem.",
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari akun perbankan.');
    }
}

