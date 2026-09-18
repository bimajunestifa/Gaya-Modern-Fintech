@extends('layouts.app')

@section('title', 'PSAK 413: Neraca, Laba Rugi & Pengungkapan CALK')
@section('header_title', 'PSAK 413: Pelaporan Keuangan & Pengungkapan Syariah')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Header Actions (No Print) -->
    <div class="no-print flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-soft">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                <span>Standar DSAS IAI • PSAK 413</span>
            </div>
            <h2 class="text-xl font-extrabold text-brand-navy font-jakarta mt-1">Laporan Keuangan &amp; Pengungkapan CALK Syariah</h2>
            <p class="text-xs text-brand-muted mt-0.5">Format pelaporan penurunan nilai aset keuangan syariah &amp; provisi kafalah</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>
            <a href="{{ route('psak413.dashboard') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                Dashboard
            </a>
        </div>
    </div>

    <!-- Printable Report Container -->
    <div class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-100 shadow-soft space-y-10">
        
        <!-- Corporate Report Letterhead -->
        <div class="border-b-2 border-emerald-700 pb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-xl shadow-md">
                    BD
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-brand-navy font-jakarta tracking-tight">Bankdash Syariah International</h1>
                    <p class="text-xs text-slate-500">Divisi Manajemen Risiko &amp; Kepatuhan Standar Akuntansi Syariah (DSAS IAI)</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-emerald-800">LAPORAN KEUANGAN PSAK 413</p>
                <p class="text-[11px] text-slate-400">Periode: {{ \Carbon\Carbon::now()->format('F Y') }}</p>
                <p class="text-[10px] text-slate-400">Mata Uang: IDR (Rupiah Penuh)</p>
            </div>
        </div>

        <!-- Section 1: Neraca Posisi Keuangan Syariah -->
        <div class="space-y-4">
            <h3 class="text-base font-extrabold text-brand-navy font-jakarta flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                <span>1. Laporan Posisi Keuangan (Neraca Aset Keuangan Syariah)</span>
            </h3>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-slate-50 text-[11px] font-extrabold text-brand-navy border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Posisi Akun Neraca Syariah</th>
                            <th class="py-3 px-4 text-center">Catatan (CALK)</th>
                            <th class="py-3 px-4 text-right">Nominal (IDR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-3 px-4 font-bold text-slate-800 pl-4">ASET KEUANGAN SYARIAH</td>
                            <td class="py-3 px-4 text-center text-slate-400"></td>
                            <td class="py-3 px-4 text-right"></td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4 text-slate-700 pl-8">Total Piutang &amp; Investasi Pembiayaan Bruto</td>
                            <td class="py-2.5 px-4 text-center text-slate-500 font-mono">Cat. 4.1</td>
                            <td class="py-2.5 px-4 text-right font-mono font-bold text-slate-800">Rp {{ number_format($totalGrossFinancing, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 px-4 text-slate-700 pl-8">Dikurangi: Pendapatan Margin Ditangguhkan</td>
                            <td class="py-2.5 px-4 text-center text-slate-500 font-mono">Cat. 4.2</td>
                            <td class="py-2.5 px-4 text-right font-mono text-slate-600">(Rp {{ number_format($totalMarginSuspended, 0, ',', '.') }})</td>
                        </tr>
                        <tr class="bg-slate-50/50 font-bold">
                            <td class="py-2.5 px-4 text-slate-800 pl-8">Nilai Tercatat Neto Pembiayaan (Gross Carrying Amount)</td>
                            <td class="py-2.5 px-4 text-center text-slate-500 font-mono"></td>
                            <td class="py-2.5 px-4 text-right font-mono text-slate-800">Rp {{ number_format($totalNetCarrying, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="text-rose-700 font-bold">
                            <td class="py-2.5 px-4 pl-8">Dikurangi: Cadangan Kerugian Penurunan Nilai (CKPN Syariah PSAK 413)</td>
                            <td class="py-2.5 px-4 text-center text-slate-500 font-mono">Cat. 4.3</td>
                            <td class="py-2.5 px-4 text-right font-mono">(Rp {{ number_format($totalEcl, 0, ',', '.') }})</td>
                        </tr>
                        <tr class="bg-emerald-50 text-emerald-900 font-extrabold text-sm border-t-2 border-emerald-600">
                            <td class="py-3 px-4 pl-4">TOTAL ASET BERSIH PEMBIAYAAN SYARIAH</td>
                            <td class="py-3 px-4 text-center"></td>
                            <td class="py-3 px-4 text-right font-mono">Rp {{ number_format($netFinancingAsset, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <td class="py-3 px-4 font-bold text-purple-900 pl-4">LIABILITAS &amp; KEWAJIBAN KONTINJENSI</td>
                            <td class="py-3 px-4 text-center text-slate-400"></td>
                            <td class="py-3 px-4 text-right"></td>
                        </tr>
                        <tr class="bg-purple-50/50">
                            <td class="py-2.5 px-4 text-purple-900 pl-8 font-semibold">Provisi Kafalah (Penjaminan Risiko Kredit Syariah)</td>
                            <td class="py-2.5 px-4 text-center text-slate-500 font-mono">Cat. 5.1</td>
                            <td class="py-2.5 px-4 text-right font-mono font-bold text-purple-800">Rp {{ number_format($totalKafalahProvision, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 2: Laporan Laba Rugi Syariah -->
        <div class="space-y-4">
            <h3 class="text-base font-extrabold text-brand-navy font-jakarta flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-600"></span>
                <span>2. Laporan Laba Rugi: Beban Kerugian Penurunan Nilai &amp; Provisi</span>
            </h3>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-slate-50 text-[11px] font-extrabold text-brand-navy border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Pos Beban Penurunan Nilai</th>
                            <th class="py-3 px-4 text-center">Akun Buku Besar</th>
                            <th class="py-3 px-4 text-right">Beban Periode Berjalan (IDR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-3 px-4 font-semibold text-slate-700">Beban Penurunan Nilai Aset Keuangan Syariah (ECL PSAK 413)</td>
                            <td class="py-3 px-4 text-center text-slate-500 font-mono">5.1.01.01</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-800">Rp {{ number_format($totalEcl, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-slate-700">Beban Pembentukan Provisi Kafalah Penjaminan</td>
                            <td class="py-3 px-4 text-center text-slate-500 font-mono">5.1.02.01</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-800">Rp {{ number_format($totalKafalahProvision, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-slate-50 font-extrabold text-sm border-t border-slate-300">
                            <td class="py-3 px-4 text-brand-navy">TOTAL BEBAN PENURUNAN NILAI &amp; PROVISI SYARIAH</td>
                            <td class="py-3 px-4 text-center"></td>
                            <td class="py-3 px-4 text-right font-mono text-rose-700">Rp {{ number_format($totalEcl + $totalKafalahProvision, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 3: Catatan Pengungkapan (CALK) Matriks Akad PSAK 413 -->
        <div class="space-y-4">
            <h3 class="text-base font-extrabold text-brand-navy font-jakarta flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                <span>3. Catatan Pengungkapan CALK: Matriks Staging per Jenis Akad Syariah</span>
            </h3>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-xs text-left border-collapse">
                    <thead class="bg-slate-50 text-[10px] font-extrabold text-brand-navy border-b border-slate-200 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-3">Jenis Akad</th>
                            <th class="py-3 px-3 text-right">Nilai Neto (EAD)</th>
                            <th class="py-3 px-3 text-right">Stage 1 (12-M)</th>
                            <th class="py-3 px-3 text-right">Stage 2 (SICR)</th>
                            <th class="py-3 px-3 text-right">Stage 3 (NPF)</th>
                            <th class="py-3 px-3 text-right">Total CKPN</th>
                            <th class="py-3 px-3 text-right">Provisi Kafalah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                        @foreach($contractBreakdowns as $row)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-3 font-sans font-bold text-slate-800">{{ $row->contract_type }}</td>
                                <td class="py-3 px-3 text-right font-bold text-slate-800">Rp {{ number_format($row->net_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-right text-emerald-700">Rp {{ number_format($row->stage1_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-right text-amber-700">Rp {{ number_format($row->stage2_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-right text-rose-700">Rp {{ number_format($row->stage3_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-right font-bold text-emerald-800">Rp {{ number_format($row->total_ecl, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-right text-purple-700">
                                    {{ $row->total_kafalah > 0 ? 'Rp ' . number_format($row->total_kafalah, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-slate-100 font-extrabold text-xs font-mono border-t-2 border-slate-300">
                            <td class="py-3 px-3 font-sans">TOTAL KONSOLIDASI</td>
                            <td class="py-3 px-3 text-right">Rp {{ number_format($totalNetCarrying, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right text-emerald-700">Rp {{ number_format($stageSummary['stage1']['exposure'], 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right text-amber-700">Rp {{ number_format($stageSummary['stage2']['exposure'], 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right text-rose-700">Rp {{ number_format($stageSummary['stage3']['exposure'], 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right text-emerald-800">Rp {{ number_format($totalEcl, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right text-purple-800">Rp {{ number_format($totalKafalahProvision, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="pt-8 border-t border-slate-200 grid grid-cols-2 sm:grid-cols-3 gap-6 text-center text-xs">
            <div>
                <p class="text-slate-400 text-[10px]">Dibuat Oleh:</p>
                <div class="h-16 flex items-end justify-center font-bold text-slate-800">
                    {{ Auth::check() ? Auth::user()->name : 'Officer PSAK 413' }}
                </div>
                <p class="text-[11px] text-slate-500 border-t border-slate-200 pt-1">Sharia Risk Analyst</p>
            </div>
            <div>
                <p class="text-slate-400 text-[10px]">Diverifikasi Oleh:</p>
                <div class="h-16 flex items-end justify-center font-bold text-slate-800">
                    Dewan Pengawas Syariah (DPS)
                </div>
                <p class="text-[11px] text-slate-500 border-t border-slate-200 pt-1">Sharia Compliance Officer</p>
            </div>
            <div class="hidden sm:block">
                <p class="text-slate-400 text-[10px]">Disetujui Oleh:</p>
                <div class="h-16 flex items-end justify-center font-bold text-slate-800">
                    Direktur Kepatuhan &amp; Risiko
                </div>
                <p class="text-[11px] text-slate-500 border-t border-slate-200 pt-1">Managing Director</p>
            </div>
        </div>

    </div>

</div>
@endsection

