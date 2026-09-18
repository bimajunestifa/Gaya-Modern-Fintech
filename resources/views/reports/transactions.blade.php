@extends('layouts.app')

@section('title', 'Laporan Transaksi & Mutasi')
@section('header_title', 'Laporan Transaksi & Mutasi Keuangan')

@section('content')
<div class="space-y-8">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Kredit (Masuk) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Total Masuk (Kredit)</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 font-jakarta mt-1">Rp {{ number_format($totalCredit, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Setoran tunai & transfer masuk</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>

        <!-- Total Debit (Keluar) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Total Keluar (Debit)</p>
                <h3 class="text-2xl font-extrabold text-brand-rose font-jakarta mt-1">Rp {{ number_format($totalDebit, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Penarikan, belanja & transfer keluar</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-brand-rose flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
            </div>
        </div>

        <!-- Net Cash Flow -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Arus Kas Bersih (Net)</p>
                <h3 class="text-2xl font-extrabold {{ $netCashflow >= 0 ? 'text-brand-blue' : 'text-amber-600' }} font-jakarta mt-1">
                    {{ $netCashflow >= 0 ? '+' : '' }}Rp {{ number_format($netCashflow, 0, ',', '.') }}
                </h3>
                <p class="text-[11px] text-slate-400 mt-1">Kredit dikurangi debit</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>

        <!-- Total Volume Transaksi -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Frekuensi Transaksi</p>
                <h3 class="text-2xl font-extrabold text-brand-navy font-jakarta mt-1">{{ $totalCount }} <span class="text-sm font-semibold text-slate-400">Mutasi</span></h3>
                <p class="text-[11px] text-slate-400 mt-1">Tercatat di pembukuan core bank</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-brand-purple flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Filter & Parameter Laporan</h3>
                <p class="text-xs text-brand-muted mt-0.5">Saring data mutasi berdasarkan rentang tanggal, rekening, atau kategori</p>
            </div>

            <!-- Export & Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Export to Excel (CSV) -->
                <a href="{{ route('transactions.export_csv', request()->query()) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
                    </svg>
                    <span>Export Excel (CSV)</span>
                </a>

                <!-- Cetak Rekening Koran -->
                <a href="{{ route('transactions.statement') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-black text-white rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Rekening Koran</span>
                </a>

                <!-- Upload CSV -->
                <a href="{{ route('csv.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-blue hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>Upload CSV Excel</span>
                </a>
            </div>
        </div>

        <!-- Filter Controls Form -->
        <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <!-- Dari Tanggal -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-muted mb-1.5">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full bg-[#F5F7FA] border border-slate-200 text-xs text-slate-800 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
            </div>

            <!-- Sampai Tanggal -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-muted mb-1.5">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full bg-[#F5F7FA] border border-slate-200 text-xs text-slate-800 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
            </div>

            <!-- Rekening -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-muted mb-1.5">Rekening Bank</label>
                <select name="account_id" class="w-full bg-[#F5F7FA] border border-slate-200 text-xs text-slate-800 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                    <option value="">Semua Rekening</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                            {{ $acc->account_name }} ({{ substr($acc->account_number, -4) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tipe Arus -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-muted mb-1.5">Tipe Mutasi</label>
                <select name="type" class="w-full bg-[#F5F7FA] border border-slate-200 text-xs text-slate-800 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                    <option value="">Semua Tipe</option>
                    <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Uang Masuk (Kredit)</option>
                    <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Uang Keluar (Debit)</option>
                </select>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-brand-muted mb-1.5">Kategori</label>
                <select name="category" class="w-full bg-[#F5F7FA] border border-slate-200 text-xs text-slate-800 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-brand-blue hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                    Terapkan
                </button>
                <a href="{{ route('transactions.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-all" title="Reset Filter">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Main Data Table -->
    <div class="bg-white rounded-3xl border border-slate-100/80 shadow-soft overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Daftar Mutasi Transaksi</h3>
                <p class="text-xs text-brand-muted mt-0.5">Menampilkan seluruh mutasi pembukuan rekening nasabah</p>
            </div>
            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                {{ $transactions->total() }} Data Ditemukan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">No Referensi</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Rekening Nasabah</th>
                        <th class="px-6 py-4">Tipe & Kategori</th>
                        <th class="px-6 py-4">Pihak / Rekanan</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-right">Nominal</th>
                        <th class="px-6 py-4 text-right">Saldo Terkini</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Reference -->
                            <td class="px-6 py-4 font-mono font-bold text-brand-blue">
                                {{ $tx->reference_no }}
                            </td>

                            <!-- Tanggal -->
                            <td class="px-6 py-4 text-slate-600 font-medium whitespace-nowrap">
                                {{ $tx->transaction_date->format('d/m/Y') }}
                            </td>

                            <!-- Account -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-brand-navy">{{ $tx->account ? $tx->account->account_name : 'N/A' }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $tx->account ? $tx->account->account_number : '-' }}</div>
                            </td>

                            <!-- Tipe & Category -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $tx->type == 'credit' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-brand-rose' }}">
                                    @if($tx->type == 'credit')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                        <span>Masuk</span>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                        </svg>
                                        <span>Keluar</span>
                                    @endif
                                </span>
                                <span class="ml-2 text-[11px] font-medium text-slate-500">{{ $tx->category }}</span>
                            </td>

                            <!-- Pihak Terkait -->
                            <td class="px-6 py-4 text-slate-700 font-medium">
                                {{ $tx->recipient_sender ?: '-' }}
                            </td>

                            <!-- Deskripsi -->
                            <td class="px-6 py-4 text-slate-600 max-w-xs truncate" title="{{ $tx->description }}">
                                {{ $tx->description }}
                            </td>

                            <!-- Nominal -->
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <span class="font-bold text-sm {{ $tx->type == 'credit' ? 'text-emerald-600' : 'text-brand-rose' }}">
                                    {{ $tx->type == 'credit' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                            </td>

                            <!-- Saldo Akhir -->
                            <td class="px-6 py-4 text-right text-slate-700 font-mono font-medium whitespace-nowrap">
                                Rp {{ number_format($tx->balance_after, 0, ',', '.') }}
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($tx->status == 'Completed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Sukses</span>
                                    </span>
                                @elseif($tx->status == 'Pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Gagal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada data transaksi yang sesuai filter.</p>
                                <p class="text-xs text-slate-400 mt-1">Coba ubah rentang tanggal atau bersihkan kata kunci pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="p-6 border-t border-slate-100 bg-[#F8FAFC]">
            {{ $transactions->links() }}
        </div>
    </div>

</div>
@endsection

