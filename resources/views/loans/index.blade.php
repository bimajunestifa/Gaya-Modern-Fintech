@extends('layouts.app')

@section('title', 'Laporan Pinjaman & Kredit')
@section('header_title', 'Portofolio Kredit & Analisis NPL')

@section('content')
<div class="space-y-8">

    <!-- KPI Loans Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Total Penyaluran Kredit</p>
                <h3 class="text-2xl font-extrabold text-brand-navy font-jakarta mt-1">Rp {{ number_format($totalPrincipal, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">{{ $activeLoansCount }} Fasilitas Aktif</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Baki Debet (Outstanding)</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 font-jakarta mt-1">Rp {{ number_format($totalRemaining, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Sisa pokok pinjaman</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Kolektibilitas 1 (Lancar)</p>
                <h3 class="text-2xl font-extrabold text-brand-navy font-jakarta mt-1">{{ $kol1 }} <span class="text-xs text-slate-400 font-medium">Debitur</span></h3>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">92.8% Kualitas Prima</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Rasio NPL Gross</p>
                <h3 class="text-2xl font-extrabold text-brand-blue font-jakarta mt-1">1.45%</h3>
                <p class="text-[11px] text-slate-400 mt-1">Batas aman OJK &lt; 5.0%</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-brand-purple flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Loan List Table -->
    <div class="bg-white rounded-3xl border border-slate-100/80 shadow-soft overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Daftar Fasilitas Kredit Aktif</h3>
                <p class="text-xs text-brand-muted mt-0.5">Pantauan portofolio pembiayaan modal kerja, investasi, dan konsumtif</p>
            </div>
            <button onclick="document.getElementById('modalNewLoan').classList.remove('hidden')" class="px-5 py-2.5 bg-brand-blue hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>+ Ajukan Kredit Baru</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">No. Akad Kredit</th>
                        <th class="px-6 py-4">Nama Debitur</th>
                        <th class="px-6 py-4">Jenis Fasilitas</th>
                        <th class="px-6 py-4 text-right">Plafon Pokok</th>
                        <th class="px-6 py-4 text-right">Suku Bunga</th>
                        <th class="px-6 py-4 text-right">Angsuran / Bln</th>
                        <th class="px-6 py-4 text-right">Sisa Baki Debet</th>
                        <th class="px-6 py-4 text-center">Status Kolektibilitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($loans as $loan)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-brand-blue">{{ $loan->loan_number }}</td>
                            <td class="px-6 py-4 font-bold text-brand-navy">{{ $loan->borrower_name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $loan->loan_type }}</td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($loan->principal_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-brand-blue">{{ $loan->interest_rate }}% p.a</td>
                            <td class="px-6 py-4 text-right font-semibold text-emerald-600">Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold font-mono">Rp {{ number_format($loan->remaining_balance, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-bold {{ str_contains($loan->npl_status, '1') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $loan->npl_status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Ajukan Kredit Baru -->
<div id="modalNewLoan" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Pengajuan Fasilitas Kredit Baru</h3>
                <p class="text-xs text-brand-muted mt-0.5">Analisis plafon pinjaman dan kalkulasi angsuran otomatis</p>
            </div>
            <button onclick="document.getElementById('modalNewLoan').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('loans.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Nama Debitur / Badan Usaha</label>
                <input type="text" name="borrower_name" required placeholder="Contoh: PT Surya Kencana Abadi" 
                       class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Jenis Fasilitas Kredit</label>
                    <select name="loan_type" required class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                        <option value="Modal Kerja (Revolving)">Modal Kerja (Revolving)</option>
                        <option value="Investasi Korporasi">Investasi Korporasi</option>
                        <option value="Kredit Kepemilikan Rumah (KPR)">KPR Perbankan</option>
                        <option value="Sindikasi & Multiguna">Sindikasi & Multiguna</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Plafon Pokok (IDR)</label>
                    <input type="number" name="principal_amount" required min="1000000" step="100000" value="500000000" 
                           class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Suku Bunga (% p.a)</label>
                    <input type="number" name="interest_rate" required min="0.1" max="100" step="0.1" value="8.5" 
                           class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                </div>
                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Tenor (Bulan)</label>
                    <input type="number" name="tenor_months" required min="1" max="360" value="36" 
                           class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 text-xs space-y-1">
                <p class="font-bold text-brand-blue flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Sistem Perhitungan Otomatis
                </p>
                <p class="text-slate-500">Angsuran per bulan dan status kolektibilitas Kol-1 (Lancar) akan dikalkulasi otomatis oleh engine perbankan sesuai regulasi OJK & BI.</p>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalNewLoan').classList.add('hidden')" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-brand-blue hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-blue/30 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Setujui & Cairkan Fasilitas</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

