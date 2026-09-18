<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user() ?? \App\Models\User::first();
        return view('settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user() ?? \App\Models\User::first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengubah profil.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        AuditLog::create([
            'action' => 'UPDATE_PROFILE',
            'user_name' => $user->name,
            'ip_address' => $request->ip(),
            'details' => "Pengguna {$user->name} memperbarui data profil akun.",
        ]);

        return back()->with('success', 'Profil dan pengaturan akun berhasil diperbarui!');
    }
}

