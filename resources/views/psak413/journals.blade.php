@extends('layouts.app')

@section('title', 'PSAK 413: Jurnal Otomatis Akuntansi Syariah')
@section('header_title', 'PSAK 413: Buku Besar & Jurnal Otomatis Syariah')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-soft">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                <span>Standar DSAS IAI • PSAK 413</span>
            </div>
            <h2 class="text-xl font-extrabold text-brand-navy font-jakarta mt-1">Jurnal Akuntansi Penurunan Nilai &amp; Provisi Kafalah</h2>
            <p class="text-xs text-brand-muted mt-0.5">Pencatatan pembukuan otomatis untuk mutasi beban dan cadangan neraca syariah</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('psak413.journals.generate') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Generate Jurnal Otomatis PSAK 413</span>
                </button>
            </form>
            <a href="{{ route('psak413.dashboard') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                Dashboard
            </a>
        </div>
    </div>

    <!-- Balance Check Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Total Debit -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Mutasi Debet</span>
            <h3 class="text-xl font-extrabold text-brand-navy font-jakarta mt-1">Rp {{ number_format($totalDebit, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1">Beban Kerugian &amp; Provisi</p>
        </div>

        <!-- Total Credit -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Mutasi Kredit</span>
            <h3 class="text-xl font-extrabold text-brand-navy font-jakarta mt-1">Rp {{ number_format($totalCredit, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-blue-600 font-semibold mt-1">Akumulasi CKPN &amp; Liabilitas</p>
        </div>

        <!-- Balance Status -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Keseimbangan</span>
                <h3 class="text-lg font-extrabold {{ $isBalanced ? 'text-emerald-700' : 'text-rose-700' }} font-jakarta mt-1">
                    {{ $isBalanced ? 'BERIMBANG (BALANCED)' : 'SELISIH (UNBALANCED)' }}
                </h3>
                <p class="text-[11px] text-slate-400 mt-1">Validasi Selisih: Rp {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}</p>
            </div>
            <div class="w-10 h-10 rounded-full {{ $isBalanced ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center font-bold">
                @if($isBalanced)
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                @endif
            </div>
        </div>
    </div>

    <!-- Journal Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-soft overflow-hidden p-6 sm:p-8 space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-extrabold text-brand-navy font-jakarta">Buku Jurnal Umum (General Ledger) PSAK 413</h3>
                <p class="text-xs text-brand-muted mt-0.5">Daftar entri jurnal otomatis dengan pencatatan debet dan kredit</p>
            </div>
            <span class="text-xs font-bold text-slate-400">{{ $journals->total() }} Baris Entri</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">
                        <th class="py-3 px-3">No Voucher / Tanggal</th>
                        <th class="py-3 px-3">Kode &amp; Nama Akun</th>
                        <th class="py-3 px-3">Akad</th>
                        <th class="py-3 px-3 text-right">Debet (IDR)</th>
                        <th class="py-3 px-3 text-right">Kredit (IDR)</th>
                        <th class="py-3 px-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($journals as $j)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-3">
                                <p class="font-mono font-bold text-slate-800">{{ $j->entry_number }}</p>
                                <p class="text-[10px] text-slate-400">{{ $j->entry_date ? $j->entry_date->format('d/m/Y') : '-' }}</p>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="font-mono font-bold text-emerald-800 bg-emerald-50 px-1.5 py-0.5 rounded text-[10px]">{{ $j->account_code }}</span>
                                <p class="font-bold text-slate-800 mt-1">{{ $j->account_name }}</p>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                    {{ $j->contract_type ?? 'SYARIAH' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-right font-mono font-bold text-emerald-700">
                                {{ $j->debit > 0 ? 'Rp ' . number_format($j->debit, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3.5 px-3 text-right font-mono font-bold text-blue-700">
                                {{ $j->credit > 0 ? 'Rp ' . number_format($j->credit, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3.5 px-3 text-slate-600 text-[11px] max-w-xs truncate" title="{{ $j->description }}">
                                {{ $j->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400 font-medium">
                                Belum ada entri jurnal yang dibukukan. Klik "Generate Jurnal Otomatis" di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            {{ $journals->links() }}
        </div>
    </div>

</div>
@endsection

