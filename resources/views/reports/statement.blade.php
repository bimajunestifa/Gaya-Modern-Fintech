@extends('layouts.app')

@section('title', 'Rekening Koran Resmi')
@section('header_title', 'Rekening Koran / Bank Statement')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Action Bar (Hidden when printing) -->
    <div class="no-print bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-brand-navy font-jakarta">Cetak Rekening Koran Nasabah</h2>
            <p class="text-xs text-brand-muted">Format standar perbankan resmi siap cetak (PDF / Kertas A4)</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Print Button -->
            <button onclick="window.print()" class="px-6 py-2.5 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-md transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>

            <!-- Export to CSV -->
            <a href="{{ route('transactions.export_csv', ['account_id' => $account ? $account->id : null]) }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
                </svg>
                <span>Export Excel</span>
            </a>
        </div>
    </div>

    <!-- Official Statement Document Sheet -->
    <div class="bg-white rounded-3xl p-10 md:p-14 border border-slate-200/80 shadow-soft text-slate-800 space-y-8 print:shadow-none print:border-none print:p-0">

        <!-- Bank Letterhead / Kop Resmi Bank -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-blue flex items-center justify-center text-white shadow-md">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 font-jakarta">BANK CENTRAL DIGITAL INDONESIA</h1>
                    <p class="text-xs text-slate-500 font-medium">Kantor Pusat Operasional & Layanan Perbankan Digital • Terdaftar & Diawasi OJK</p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded bg-slate-100 text-slate-800 font-mono font-bold text-xs">REKENING KORAN RESMI</span>
                <p class="text-[11px] text-slate-400 mt-1">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Account Holder & Period Summary -->
        <div class="grid grid-cols-2 gap-8 text-xs">
            <div class="space-y-1.5 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Informasi Pemilik Rekening</p>
                <p class="text-base font-extrabold text-slate-900">{{ $account->account_name }}</p>
                <p class="font-mono text-slate-600">Nomor Rekening : <strong class="text-slate-900">{{ $account->account_number }}</strong></p>
                <p class="text-slate-600">Jenis Rekening &nbsp;: {{ $account->account_type }} ({{ $account->currency }})</p>
                <p class="text-slate-600">Status Rekening : <span class="text-emerald-700 font-bold">{{ $account->status }}</span></p>
            </div>

            <div class="space-y-1.5 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Ringkasan Mutasi Periode</p>
                <p class="text-slate-600">Periode Transaksi : <strong class="text-slate-900">{{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</strong></p>
                <p class="text-slate-600">Total Kredit (Masuk) : <strong class="text-emerald-600">Rp {{ number_format($totalCredit, 2, ',', '.') }}</strong></p>
                <p class="text-slate-600">Total Debit (Keluar) : <strong class="text-rose-600">Rp {{ number_format($totalDebit, 2, ',', '.') }}</strong></p>
                <p class="text-sm font-bold text-slate-900 pt-1 border-t border-slate-200">
                    Saldo Akhir Periode : <span class="text-brand-blue font-mono font-extrabold">Rp {{ number_format($account->balance, 2, ',', '.') }}</span>
                </p>
            </div>
        </div>

        <!-- Transaction Statement Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border border-slate-200">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold text-[11px] uppercase tracking-wider border-b border-slate-200">
                        <th class="p-3 border-r border-slate-200">Tanggal</th>
                        <th class="p-3 border-r border-slate-200">No. Referensi</th>
                        <th class="p-3 border-r border-slate-200">Keterangan / Uraian</th>
                        <th class="p-3 text-right border-r border-slate-200">Debit (Keluar)</th>
                        <th class="p-3 text-right border-r border-slate-200">Kredit (Masuk)</th>
                        <th class="p-3 text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-[11px]">
                    @forelse($transactions as $tx)
                        <tr>
                            <td class="p-3 font-medium whitespace-nowrap border-r border-slate-200">{{ $tx->transaction_date->format('d/m/Y') }}</td>
                            <td class="p-3 font-mono font-semibold text-slate-700 border-r border-slate-200">{{ $tx->reference_no }}</td>
                            <td class="p-3 text-slate-800 border-r border-slate-200">
                                <p class="font-semibold">{{ $tx->description }}</p>
                                <p class="text-[10px] text-slate-500">{{ $tx->recipient_sender ?: $tx->category }}</p>
                            </td>
                            <td class="p-3 text-right font-mono font-medium text-rose-600 border-r border-slate-200">
                                {{ $tx->type == 'debit' ? number_format($tx->amount, 2, ',', '.') : '-' }}
                            </td>
                            <td class="p-3 text-right font-mono font-medium text-emerald-600 border-r border-slate-200">
                                {{ $tx->type == 'credit' ? number_format($tx->amount, 2, ',', '.') : '-' }}
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-slate-900">
                                {{ number_format($tx->balance_after, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400 italic">Tidak ada transaksi tercatat pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bank Signatory & Legal Disclaimer -->
        <div class="pt-8 grid grid-cols-2 gap-8 text-xs items-end">
            <div class="text-slate-500 text-[11px] leading-relaxed">
                <p class="font-bold text-slate-700 mb-1">Catatan Keabsahan Dokumen:</p>
                <p>Dokumen rekening koran ini dicetak secara sah oleh sistem core banking komputerisasi resmi Bank Central Digital Indonesia dan dilindungi enkripsi data perbankan nasional.</p>
            </div>

            <div class="text-center space-y-12">
                <p class="text-xs text-slate-600 font-semibold">Jakarta, {{ date('d F Y') }}<br>A.n. Pimpinan Operasional Cabang</p>
                <div>
                    <p class="font-bold text-slate-900 underline">Eddy Cusuma, S.E., M.M.</p>
                    <p class="text-[11px] text-slate-500">Branch Operations Manager (ID: 994012)</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

