@extends('layouts.app')

@section('title', 'Pengaturan Akun & Profil')
@section('header_title', 'Setting')

@section('content')
<div class="space-y-8 max-w-4xl mx-auto">

    <!-- Profile Header Card -->
    <div class="bg-white rounded-3xl p-8 border border-slate-100/80 shadow-soft flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <div class="relative">
                <img class="w-20 h-20 rounded-full object-cover ring-4 ring-blue-50 shadow-md" 
                     src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80" 
                     alt="{{ $user ? $user->name : 'User' }}">
                <div class="absolute bottom-0 right-0 w-6 h-6 rounded-full bg-brand-blue text-white flex items-center justify-center ring-2 ring-white cursor-pointer shadow-sm" title="Ubah Foto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </div>
            </div>

            <div>
                <h2 class="text-2xl font-extrabold text-brand-navy font-jakarta">{{ $user ? $user->name : 'Eddy Cusuma' }}</h2>
                <p class="text-xs font-semibold text-brand-muted mt-0.5">{{ $user ? $user->email : 'admin@bankdash.com' }}</p>
                <div class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full bg-blue-50 text-brand-blue text-[11px] font-bold">
                    <span class="w-2 h-2 rounded-full bg-brand-blue"></span>
                    <span>Senior Branch Manager • Hak Akses Penuh</span>
                </div>
            </div>
        </div>

        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-rose-50 hover:bg-rose-100 text-brand-rose rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white rounded-3xl p-8 border border-slate-100/80 shadow-soft space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-brand-navy font-jakarta">Informasi Akun & Kredensial</h3>
            <p class="text-xs text-brand-muted mt-0.5">Perbarui nama tampilan, email resmi, dan ganti kata sandi perbankan</p>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user ? $user->name : '') }}" required 
                           class="w-full bg-[#F5F7FA] border border-slate-200 text-sm font-semibold text-slate-800 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                </div>

                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">Alamat Email Resmi</label>
                    <input type="email" name="email" value="{{ old('email', $user ? $user->email : '') }}" required 
                           class="w-full bg-[#F5F7FA] border border-slate-200 text-sm font-semibold text-slate-800 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-sm font-bold text-brand-navy mb-4">Ganti Kata Sandi (Opsional)</h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[11px] font-bold text-brand-muted uppercase tracking-wider mb-1.5">Sandi Saat Ini</label>
                        <input type="password" name="current_password" placeholder="••••••••" 
                               class="w-full bg-[#F5F7FA] border border-slate-200 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-brand-muted uppercase tracking-wider mb-1.5">Sandi Baru</label>
                        <input type="password" name="new_password" placeholder="Minimal 6 karakter" 
                               class="w-full bg-[#F5F7FA] border border-slate-200 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-brand-muted uppercase tracking-wider mb-1.5">Ulangi Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" placeholder="Konfirmasi sandi baru" 
                               class="w-full bg-[#F5F7FA] border border-slate-200 text-sm rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
                <button type="submit" class="px-8 py-3.5 bg-brand-blue hover:bg-blue-700 text-white font-bold text-xs rounded-2xl shadow-md shadow-brand-blue/30 transition-all active:scale-95">
                    Simpan Perubahan Akun
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

