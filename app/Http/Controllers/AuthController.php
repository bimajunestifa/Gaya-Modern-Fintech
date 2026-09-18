<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AuditLog;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            AuditLog::create([
                'action' => 'USER_LOGIN',
                'user_name' => Auth::user()->name,
                'ip_address' => $request->ip(),
                'details' => "Pengguna " . Auth::user()->name . " (" . Auth::user()->email . ") berhasil login ke sistem.",
            ]);

            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        AuditLog::create([
            'action' => 'USER_REGISTER',
            'user_name' => $user->name,
            'ip_address' => $request->ip(),
            'details' => "Pendaftaran akun baru {$user->email} berhasil.",
        ]);

        return redirect()->route('dashboard')->with('success', 'Akun perbankan Anda berhasil didaftarkan! Selamat datang, ' . $user->name);
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return back()->with('success', 'Tautan instruksi reset kata sandi telah dikirimkan ke email Anda (Mode Simulasi Bank).');
    }

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

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari akun.');
    }
}

