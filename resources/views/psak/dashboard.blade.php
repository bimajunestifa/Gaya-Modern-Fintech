@extends('layouts.app')

@section('title', 'PSAK 71: ECL & Staging Engine')
@section('header_title', 'PSAK 71: Expected Credit Loss (ECL) & Staging')

@section('content')
<div class="space-y-8">

    <!-- Hero Banner with Magnific Finance SVG Vector Illustration -->
    <div class="relative overflow-hidden bg-gradient-to-r from-[#1814F3] via-brand-blue to-indigo-900 rounded-3xl p-8 text-white shadow-card flex flex-col lg:flex-row items-center justify-between gap-8">
        <div class="space-y-3 z-10 max-w-xl">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/15 text-blue-100 text-xs font-semibold backdrop-blur-sm">
                <!-- Magnific SVG Shield Icon -->
                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>Standar Kepatuhan Akuntansi Perbankan Indonesia (PSAK 71 / IFRS 9)</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold font-jakarta tracking-tight">Kalkulator Kuantitatif CKPN & Staging 3 Tingkat</h2>
            <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                Pemantauan risiko penurunan nilai kredit secara real-time berdasarkan formula kuantitatif <strong class="text-white">EAD × PD × LGD</strong> dengan pemodelan makroekonomi *forward-looking* dan diskonto suku bunga efektif.
            </p>
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="{{ route('psak.stresstest') }}" class="px-5 py-2.5 bg-white text-brand-blue hover:bg-blue-50 font-bold rounded-xl text-xs shadow-md transition-all">
                    Buka Simulator Stresstest Makro &rarr;
                </a>
                <a href="{{ route('psak.import') }}" class="px-5 py-2.5 bg-blue-600/60 hover:bg-blue-600 text-white font-bold rounded-xl text-xs border border-white/20 transition-all">
                    Upload Batch Excel (CSV)
                </a>
            </div>
        </div>

        <!-- Magnific Vector Illustration: Finance Growth & Analyst (SVG Artwork) -->
        <div class="z-10 flex-shrink-0 w-64 sm:w-72 h-48 relative flex items-center justify-center">
            <svg viewBox="0 0 400 300" class="w-full h-full drop-shadow-xl" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Monitor Screen -->
                <rect x="50" y="40" width="280" height="190" rx="16" fill="#FFFFFF" fill-opacity="0.95" stroke="#E2E8F0" stroke-width="4"/>
                <rect x="160" y="230" width="60" height="40" fill="#CBD5E1"/>
                <rect x="130" y="270" width="120" height="12" rx="6" fill="#94A3B8"/>
                
                <!-- Screen Content: Financial Graphs -->
                <path d="M70 180 L110 140 L160 160 L210 100 L260 120 L300 70" stroke="#2D60FF" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M70 180 L110 140 L160 160 L210 100 L260 120 L300 70 L300 210 L70 210 Z" fill="url(#screenGrad)" opacity="0.25"/>
                
                <!-- Upward Arrow Vector -->
                <path d="M280 60 L310 60 L310 90 M310 60 L260 110" stroke="#10B981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                
                <!-- Financial Buildings / Bar silhouette inside screen -->
                <rect x="230" y="140" width="16" height="70" fill="#E2E8F0"/>
                <rect x="250" y="120" width="18" height="90" fill="#CBD5E1"/>
                <rect x="272" y="100" width="16" height="110" fill="#94A3B8"/>

                <!-- Gold Coins Stack Vector (Magnific Style) -->
                <ellipse cx="90" cy="240" rx="22" ry="7" fill="#F59E0B"/>
                <ellipse cx="90" cy="235" rx="22" ry="7" fill="#FBBF24"/>
                <ellipse cx="90" cy="230" rx="22" ry="7" fill="#FCD34D"/>
                <ellipse cx="90" cy="225" rx="22" ry="7" fill="#FDE68A"/>

                <!-- Financial Analyst Vector Figure (Magnific Silhouette) -->
                <circle cx="110" cy="115" r="14" fill="#1E293B"/>
                <path d="M100 135 C100 128 120 128 120 135 L125 180 L95 180 Z" fill="#1E293B"/>
                <!-- Clipboard / Tablet in Hand -->
                <rect x="112" y="142" width="16" height="22" rx="3" fill="#2D60FF"/>
                <rect x="115" y="146" width="10" height="2" fill="#FFFFFF"/>
                <rect x="115" y="150" width="8" height="2" fill="#FFFFFF"/>

                <defs>
                    <linearGradient id="screenGrad" x1="185" y1="70" x2="185" y2="210" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#2D60FF"/>
                        <stop offset="1" stop-color="#FFFFFF" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <!-- Decorative background circle -->
        <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-white/10 rounded-full pointer-events-none"></div>
    </div>

    <!-- KPI Staging Cards (Stage 1, Stage 2, Stage 3, Total CKPN) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Stage 1: Performing (12-Month ECL) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                    Stage 1 • 12-M ECL
                </span>
                <span class="text-xs font-bold text-slate-400">{{ $stage1Count }} Debitur</span>
            </div>

            <div class="mt-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-brand-muted">Eksposur Kredit (EAD)</p>
                <h3 class="text-xl font-extrabold text-brand-navy font-jakarta mt-0.5">Rp {{ number_format($stage1Ead, 0, ',', '.') }}</h3>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Cadangan CKPN</span>
                <span class="font-extrabold text-emerald-600">Rp {{ number_format($stage1Ecl, 0, ',', '.') }}</span>
            </div>
            <!-- Bottom green bar -->
            <div class="absolute bottom-0 inset-x-0 h-1 bg-emerald-500"></div>
        </div>

        <!-- Stage 2: SICR (Lifetime ECL) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider">
                    Stage 2 • Lifetime (SICR)
                </span>
                <span class="text-xs font-bold text-slate-400">{{ $stage2Count }} Debitur</span>
            </div>

            <div class="mt-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-brand-muted">Eksposur Kredit (EAD)</p>
                <h3 class="text-xl font-extrabold text-brand-navy font-jakarta mt-0.5">Rp {{ number_format($stage2Ead, 0, ',', '.') }}</h3>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Cadangan CKPN</span>
                <span class="font-extrabold text-amber-600">Rp {{ number_format($stage2Ecl, 0, ',', '.') }}</span>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-amber-500"></div>
        </div>

        <!-- Stage 3: Impaired (Default) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-brand-rose border border-rose-200 uppercase tracking-wider">
                    Stage 3 • Impaired / NPL
                </span>
                <span class="text-xs font-bold text-slate-400">{{ $stage3Count }} Debitur</span>
            </div>

            <div class="mt-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-brand-muted">Eksposur Kredit (EAD)</p>
                <h3 class="text-xl font-extrabold text-brand-navy font-jakarta mt-0.5">Rp {{ number_format($stage3Ead, 0, ',', '.') }}</h3>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Cadangan CKPN</span>
                <span class="font-extrabold text-brand-rose">Rp {{ number_format($stage3Ecl, 0, ',', '.') }}</span>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-rose-500"></div>
        </div>

        <!-- Total Akumulasi CKPN & Coverage Ratio -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-brand-blue border border-blue-200 uppercase tracking-wider">
                    Total CKPN Bank
                </span>
                <span class="text-xs font-bold text-emerald-600">{{ number_format($totalCoverage, 2) }}% Ratio</span>
            </div>

            <div class="mt-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-brand-muted">Cadangan Wajib Dibentuk</p>
                <h3 class="text-xl font-extrabold text-brand-blue font-jakarta mt-0.5">Rp {{ number_format($totalEcl, 0, ',', '.') }}</h3>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">NPL Coverage</span>
                <span class="font-extrabold text-brand-blue">{{ number_format($coverageRatio, 1) }}% (Aman)</span>
            </div>
            <div class="absolute bottom-0 inset-x-0 h-1 bg-brand-blue"></div>
        </div>

    </div>

    <!-- PHOENIX LINE CHART SECTION (Requested Specifically by User) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Phoenix Line Chart (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-7 border border-slate-100/80 shadow-soft space-y-6">
            
            <!-- Phoenix Top Header & Metrics Bar (Image 1 Style) -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-lg font-bold text-brand-navy font-jakarta">Tren Pertumbuhan Portofolio & Proyeksi CKPN</h3>
                    <p class="text-xs text-brand-muted mt-0.5">Trajektori total eksposur kredit terhadap pembentukan cadangan kerugian penurunan nilai</p>
                </div>

                <!-- Date Range Selector (Phoenix Dropdown Style) -->
                <div class="inline-flex items-center gap-2 bg-[#F5F7FA] border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-700">
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <!-- Phoenix Quick Metrics Pill Row -->
            <div class="flex flex-wrap items-center gap-6 text-xs font-semibold text-slate-600 bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span><strong>{{ number_format($totalPortfoliosCount, 0, ',', '.') }}</strong> Fasilitas Kredit Aktif</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <span><strong>{{ number_format($stage2Count, 0, ',', '.') }}</strong> Watchlist SICR (Stage 2)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span><strong>{{ number_format($collateralCount, 0, ',', '.') }}</strong> Agunan Terdaftar</span>
                </div>
                <div class="ml-auto flex items-center gap-4 text-[11px]">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-1 bg-brand-blue rounded"></span> Eksposur Gross (Miliar)</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-1 bg-brand-teal border-dashed rounded"></span> Cadangan CKPN (Juta)</span>
                </div>
            </div>

            <!-- Canvas Phoenix Line Chart -->
            <div class="h-72 relative">
                <canvas id="phoenixEclChart"></canvas>
            </div>
        </div>

        <!-- Staging Distribution Donut & Macro Assumptions (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Staging Donut -->
            <div class="bg-white rounded-3xl p-7 border border-slate-100/80 shadow-soft space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-brand-navy font-jakarta">Distribusi Risiko Staging</h3>
                    <span class="text-[10px] font-bold text-brand-blue bg-blue-50 px-2.5 py-0.5 rounded-full">PSAK 71</span>
                </div>

                <div class="h-48 relative flex items-center justify-center">
                    <canvas id="psakStagingDonutChart"></canvas>
                </div>

                <div class="pt-2 border-t border-slate-100 text-xs space-y-2">
                    <div class="flex justify-between items-center text-slate-600">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-brand-teal"></span> Stage 1 (Performing)</span>
                        <span class="font-bold text-brand-navy">{{ number_format(($stage1Ead / max(1, $totalEad)) * 100, 1) }}%</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-brand-amber"></span> Stage 2 (SICR)</span>
                        <span class="font-bold text-brand-navy">{{ number_format(($stage2Ead / max(1, $totalEad)) * 100, 1) }}%</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-600">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-brand-rose"></span> Stage 3 (Impaired)</span>
                        <span class="font-bold text-brand-navy">{{ number_format(($stage3Ead / max(1, $totalEad)) * 100, 1) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Forward-Looking Macro Parameters Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-brand-navy">Parameter Makro Acuan</h4>
                    <a href="{{ route('psak.stresstest') }}" class="text-[11px] font-bold text-brand-blue hover:underline">Stresstest &rarr;</a>
                </div>
                <div class="space-y-2 text-xs">
                    @foreach($macroParameters as $macro)
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-brand-navy text-[11px]">{{ $macro->scenario_name }}</p>
                                <p class="text-[10px] text-slate-400">BI: {{ $macro->bi_rate }}% • Inflasi: {{ $macro->inflation_rate }}%</p>
                            </div>
                            <span class="font-mono font-bold text-brand-blue text-xs">{{ $macro->weight_percentage }}% Bobot</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Debtor Portfolios & ECL Calculation Table -->
    <div class="bg-white rounded-3xl border border-slate-100/80 shadow-soft overflow-hidden space-y-0">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Daftar Portofolio Kredit & Perhitungan Nilai CKPN</h3>
                <p class="text-xs text-brand-muted mt-0.5">Rincian parameter kuantitatif EAD, PD, LGD, nilai agunan terdiskon, dan alokasi cadangan</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('psak.download_template') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                    Unduh Template Excel
                </a>
                <a href="{{ route('psak.import') }}" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>Impor Kredit Baru (CSV)</span>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">No Fasilitas</th>
                        <th class="px-6 py-4">Nama Debitur & Sektor</th>
                        <th class="px-6 py-4 text-right">Baki Debet (EAD)</th>
                        <th class="px-6 py-4 text-right">Nilai Agunan</th>
                        <th class="px-6 py-4 text-center">Tunggakan (DPD)</th>
                        <th class="px-6 py-4 text-center">Staging PSAK</th>
                        <th class="px-6 py-4 text-center">PD / LGD</th>
                        <th class="px-6 py-4 text-right">Cadangan CKPN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($portfolios as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-brand-blue">{{ $p->account_number }}</td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-brand-navy">{{ $p->borrower_name }}</p>
                                <p class="text-[11px] text-slate-400">{{ $p->facility_type }} • {{ $p->sector }}</p>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-800 whitespace-nowrap">
                                Rp {{ number_format($p->outstanding_balance, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-slate-600 whitespace-nowrap">
                                Rp {{ number_format($p->collateral_value, 0, ',', '.') }}
                                <p class="text-[10px] text-slate-400">{{ $p->collateral_type }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-mono font-bold {{ $p->dpd > 90 ? 'text-brand-rose' : ($p->dpd > 30 ? 'text-amber-600' : 'text-emerald-600') }}">
                                    {{ $p->dpd }} Hari
                                </span>
                                @if($p->is_restructured)
                                    <span class="block text-[9px] font-bold text-purple-600 uppercase">Restrukturisasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($p->stage == 1)
                                    <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Stage 1 (12-M)
                                    </span>
                                @elseif($p->stage == 2)
                                    <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        Stage 2 (SICR)
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-brand-rose border border-rose-200">
                                        Stage 3 (Default)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-[11px] whitespace-nowrap">
                                <span class="font-bold text-slate-800">{{ $p->pd_rate }}%</span> / <span class="text-slate-500">{{ $p->lgd_rate }}%</span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-extrabold text-sm whitespace-nowrap {{ $p->stage == 3 ? 'text-brand-rose' : ($p->stage == 2 ? 'text-amber-600' : 'text-emerald-600') }}">
                                Rp {{ number_format($p->ecl_allowance, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. PHOENIX LINE CHART IMPLEMENTATION (Image 1 Style: Dual Spline Curve with Solid Blue & Dotted Teal)
        const phoenixCtx = document.getElementById('phoenixEclChart').getContext('2d');
        
        // Gradient fill for solid curve
        const phoenixGrad = phoenixCtx.createLinearGradient(0, 0, 0, 240);
        phoenixGrad.addColorStop(0, 'rgba(45, 96, 255, 0.20)');
        phoenixGrad.addColorStop(1, 'rgba(45, 96, 255, 0.0)');

        new Chart(phoenixCtx, {
            type: 'line',
            data: {
                labels: @json($phoenixChartLabels),
                datasets: [
                    {
                        label: 'Gross Loan Portfolio (Miliar)',
                        data: @json($phoenixGrossTrajectory),
                        borderColor: '#2D60FF',
                        borderWidth: 3.5,
                        tension: 0.45,
                        fill: true,
                        backgroundColor: phoenixGrad,
                        pointRadius: 4,
                        pointBackgroundColor: '#2D60FF',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    },
                    {
                        label: 'ECL Allowance (Ratusan Juta)',
                        data: @json($phoenixEclTrajectory),
                        borderColor: '#16DBCC',
                        borderWidth: 2.5,
                        borderDash: [5, 5], // Dotted line matching Phoenix
                        tension: 0.45,
                        fill: false,
                        pointRadius: 3,
                        pointBackgroundColor: '#16DBCC',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        titleFont: { family: 'Inter', size: 12, weight: 600 },
                        bodyFont: { family: 'Inter', size: 11 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#718EBF', font: { family: 'Inter', size: 11 } }
                    },
                    y: {
                        grid: { color: '#F1F5F9' },
                        ticks: { color: '#718EBF', font: { family: 'Inter', size: 11 } },
                        beginAtZero: true
                    }
                }
            }
        });

        // 2. Staging Donut Chart
        const stagingCtx = document.getElementById('psakStagingDonutChart').getContext('2d');
        new Chart(stagingCtx, {
            type: 'doughnut',
            data: {
                labels: @json($stagingDonut['labels']),
                datasets: [{
                    data: @json($stagingDonut['data']),
                    backgroundColor: ['#16DBCC', '#FFBB38', '#FE5C73'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush

