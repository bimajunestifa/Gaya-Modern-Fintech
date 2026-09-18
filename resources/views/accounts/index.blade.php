@extends('layouts.app')

@section('title', 'Rekening & Kartu Bank')
@section('header_title', 'Manajemen Rekening & Kartu Nasabah')

@section('content')
<div class="space-y-8">

    <!-- Header & New Account Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-brand-navy font-jakarta">Daftar Rekening Terdaftar</h2>
            <p class="text-xs text-brand-muted mt-0.5">Kelola data simpanan tabungan, giro usaha, dan kartu debit/kredit perbankan</p>
        </div>

        <button onclick="document.getElementById('modalNewAccount').classList.remove('hidden')" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-brand-blue hover:bg-blue-700 text-white rounded-2xl text-xs font-bold shadow-md shadow-brand-blue/30 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Buka Rekening Baru</span>
        </button>
    </div>

    <!-- Accounts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($accounts as $acc)
            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-blue-50 text-brand-blue">
                            {{ $acc->account_type }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            {{ $acc->status }}
                        </span>
                    </div>

                    <h4 class="text-lg font-bold text-brand-navy mt-4">{{ $acc->account_name }}</h4>
                    <p class="text-xs font-mono text-slate-400 mt-0.5">{{ $acc->account_number }}</p>

                    <div class="mt-5 p-4 rounded-2xl bg-[#F5F7FA]">
                        <p class="text-[10px] uppercase font-bold tracking-wider text-brand-muted">Saldo Efektif</p>
                        <p class="text-xl font-extrabold text-brand-navy font-jakarta mt-1">Rp {{ number_format($acc->balance, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-medium">{{ $acc->transactions_count }} Transaksi</span>
                    <a href="{{ route('transactions.statement', $acc->id) }}" class="font-bold text-brand-blue hover:underline flex items-center gap-1">
                        <span>Rekening Koran</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Bank Cards Section -->
    <div class="space-y-4 pt-4">
        <h3 class="text-lg font-bold text-brand-navy font-jakarta">Kartu Debit & Kredit Virtual Terdaftar</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($cards as $card)
                <div class="relative overflow-hidden rounded-3xl p-6 shadow-card flex flex-col justify-between h-52 
                     {{ $card->theme_style == 'blue' ? 'bg-gradient-to-tr from-[#0A06F4] via-[#2D60FF] to-[#4C49ED] text-white' : ($card->theme_style == 'dark' ? 'bg-gradient-to-tr from-slate-900 via-slate-800 to-slate-950 text-white' : 'bg-white text-brand-navy border border-slate-100 shadow-soft') }}">
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider {{ $card->theme_style == 'white' ? 'text-brand-muted' : 'text-blue-100' }}">{{ $card->tier }}</span>
                            <h4 class="text-xl font-extrabold tracking-tight mt-0.5 font-jakarta">Rp {{ number_format($card->balance, 0, ',', '.') }}</h4>
                        </div>
                        <div class="w-8 h-6 rounded bg-amber-400/80 p-0.5 flex items-center justify-center opacity-90">
                            <div class="w-full h-full border border-amber-800/40 rounded-sm"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-8 text-[11px]">
                        <div>
                            <p class="text-[9px] uppercase tracking-wider {{ $card->theme_style == 'white' ? 'text-brand-muted' : 'text-blue-200' }}">HOLDER</p>
                            <p class="font-bold tracking-wide">{{ $card->card_holder }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] uppercase tracking-wider {{ $card->theme_style == 'white' ? 'text-brand-muted' : 'text-blue-200' }}">EXPIRES</p>
                            <p class="font-bold tracking-wide">{{ $card->valid_thru }}</p>
                        </div>
                    </div>

                    <div class="pt-3 border-t {{ $card->theme_style == 'white' ? 'border-slate-100' : 'border-white/20' }} flex items-center justify-between">
                        <span class="text-base font-mono font-bold tracking-widest">{{ $card->card_number }}</span>
                        <div class="flex -space-x-2">
                            <div class="w-5 h-5 rounded-full bg-red-500/70"></div>
                            <div class="w-5 h-5 rounded-full bg-amber-500/70"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Modal Buka Rekening Baru -->
<div id="modalNewAccount" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-brand-navy font-jakarta">Buka Rekening Nasabah Baru</h3>
            <button onclick="document.getElementById('modalNewAccount').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('accounts.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Nomor Rekening</label>
                <input type="text" name="account_number" required placeholder="Contoh: 1029-8832-9901" 
                       class="w-full bg-[#F5F7FA] border border-slate-200 text-xs font-mono rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
            </div>

            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Nama Lengkap / Perusahaan</label>
                <input type="text" name="account_name" required placeholder="Contoh: PT Sumber Rezeki Mandiri" 
                       class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
            </div>

            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Jenis Produk Rekening</label>
                <select name="account_type" required class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                    <option value="Tabungan Bisnis Premier">Tabungan Bisnis Premier</option>
                    <option value="Giro Utama Komersial">Giro Utama Komersial</option>
                    <option value="Deposito Berjangka">Deposito Berjangka</option>
                    <option value="Tabungan Reguler">Tabungan Reguler</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Setoran Awal (Nominal IDR)</label>
                <input type="number" name="balance" required min="0" value="10000000" 
                       class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalNewAccount').classList.add('hidden')" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-brand-blue hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-blue/30">
                    Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

