@extends('layouts.app')

@section('title', 'Jurnal Otomatis PSAK 71')
@section('header_title', 'Jurnal Akuntansi Otomatis (General Ledger PSAK)')

@section('content')
<div class="space-y-8">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Total Mutasi Debet</p>
                <h3 class="text-2xl font-extrabold text-brand-navy font-jakarta mt-1 font-mono">Rp {{ number_format($totalDebit, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Beban CKPN & Biaya Amortisasi</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Total Mutasi Kredit</p>
                <h3 class="text-2xl font-extrabold text-brand-navy font-jakarta mt-1 font-mono">Rp {{ number_format($totalCredit, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Cadangan Kontra Aset & Akrual</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Status Validasi Pembukuan</p>
                @if($isBalanced)
                    <h3 class="text-2xl font-extrabold text-emerald-600 font-jakarta mt-1">Balanced (100%)</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1">Debet = Kredit Sempurna</p>
                @else
                    <h3 class="text-2xl font-extrabold text-brand-rose font-jakarta mt-1">Selisih</h3>
                    <p class="text-[11px] text-rose-500 font-semibold mt-1">Periksa entri jurnal</p>
                @endif
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Journal Entries Table -->
    <div class="bg-white rounded-3xl border border-slate-100/80 shadow-soft overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Buku Jurnal Umum PSAK 71</h3>
                <p class="text-xs text-brand-muted mt-0.5">Ayat jurnal akuntansi pembentukan dan penyesuaian cadangan kerugian penurunan nilai</p>
            </div>
            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                {{ $entries->total() }} Ayat Jurnal
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">Nomor Jurnal / Tanggal</th>
                        <th class="px-6 py-4">Kode & Nama Akun (Chart of Accounts)</th>
                        <th class="px-6 py-4">Uraian / Deskripsi Transaksi</th>
                        <th class="px-6 py-4 text-right">Debet (Rp)</th>
                        <th class="px-6 py-4 text-right">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($entries as $entry)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-mono font-bold text-brand-blue">{{ $entry->entry_number }}</p>
                                <p class="text-[11px] text-slate-400">{{ $entry->entry_date->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-[10px]">{{ $entry->account_code }}</span>
                                <p class="font-bold text-brand-navy mt-1">{{ $entry->account_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-600 max-w-md">
                                {{ $entry->description }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                {{ $entry->debit > 0 ? number_format($entry->debit, 2, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-900 whitespace-nowrap">
                                {{ $entry->credit > 0 ? number_format($entry->credit, 2, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada ayat jurnal akuntansi tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 bg-[#F8FAFC]">
            {{ $entries->links() }}
        </div>
    </div>

</div>
@endsection

