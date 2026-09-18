@extends('layouts.app')

@section('title', 'PSAK 413: Penurunan Nilai Pembiayaan Syariah & Kafalah')
@section('header_title', 'PSAK 413: Ekspektasi Kerugian Syariah & Provisi Kafalah')

@section('content')
<div class="space-y-8">

    <!-- Hero Banner with Islamic Finance Emerald Aesthetic -->
    <div class="relative overflow-hidden bg-gradient-to-r from-emerald-800 via-teal-700 to-emerald-950 rounded-3xl p-8 text-white shadow-card flex flex-col lg:flex-row items-center justify-between gap-8">
        <div class="space-y-3 z-10 max-w-xl">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/15 text-emerald-100 text-xs font-semibold backdrop-blur-sm">
                <!-- Shield Icon -->
                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>Standar DSAS IAI Juli 2024 • Efektif 1 Jan 2027</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold font-jakarta tracking-tight">PSAK 413: Penurunan Nilai Aset Syariah & Provisi Kafalah</h2>
            <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">
                Pemodelan ekspektasi kerugian (*Expected Loss*) berdasarkan akad-akad syariah (<strong class="text-white">Murabahah, Musyarakah, Mudharabah, Ijarah, Istishna', Qardh, dan Kafalah</strong>) dengan 3-Stage ECL, haircut agunan syariah, dan pencadangan kewajiban kontinjensi.
            </p>
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="{{ route('psak413.stresstest') }}" class="px-5 py-2.5 bg-white text-emerald-800 hover:bg-emerald-50 font-bold rounded-xl text-xs shadow-md transition-all">
                    Stress Test Makro Syariah &rarr;
                </a>
                <a href="{{ route('psak413.import') }}" class="px-5 py-2.5 bg-emerald-600/60 hover:bg-emerald-600 text-white font-bold rounded-xl text-xs border border-white/20 transition-all">
                    Batch Import Excel Akad (CSV)
                </a>
                <a href="{{ route('psak413.reports') }}" class="px-5 py-2.5 bg-teal-900/60 hover:bg-teal-900 text-white font-bold rounded-xl text-xs border border-white/20 transition-all">
                    CALK &amp; Neraca Syariah
                </a>
            </div>
        </div>

        <!-- Islamic Banking Vector Illustration -->
        <div class="z-10 flex-shrink-0 w-64 sm:w-72 h-48 relative flex items-center justify-center">
            <svg viewBox="0 0 400 300" class="w-full h-full drop-shadow-xl" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Dome Silhouette & Modern Building -->
                <rect x="50" y="40" width="280" height="190" rx="16" fill="#FFFFFF" fill-opacity="0.95" stroke="#E2E8F0" stroke-width="4"/>
                <rect x="160" y="230" width="60" height="40" fill="#CBD5E1"/>
                <rect x="130" y="270" width="120" height="12" rx="6" fill="#94A3B8"/>
                
                <!-- Mosque Arch on Screen -->
                <path d="M140 180 C140 120 240 120 240 180 L240 210 L140 210 Z" fill="#10B981" fill-opacity="0.15"/>
                <path d="M140 180 C140 120 240 120 240 180" stroke="#059669" stroke-width="3"/>
                <circle cx="190" cy="115" r="8" fill="#F59E0B"/>
                
                <!-- Financial Curve -->
                <path d="M70 170 L120 140 L170 150 L220 100 L270 110 L305 65" stroke="#10B981" stroke-width="4" stroke-linecap="round"/>
                
                <!-- Gold Crescent & Star Vector -->
                <path d="M295 85 A12 12 0 1 0 315 105 A15 15 0 1 1 295 85 Z" fill="#FBBF24"/>
                <polygon points="318,85 320,90 325,90 321,93 323,98 318,95 314,98 316,93 312,90 317,90" fill="#F59E0B"/>

                <!-- Gold Stacks -->
                <ellipse cx="90" cy="240" rx="22" ry="7" fill="#059669"/>
                <ellipse cx="90" cy="235" rx="22" ry="7" fill="#10B981"/>
                <ellipse cx="90" cy="230" rx="22" ry="7" fill="#34D399"/>
                <ellipse cx="90" cy="225" rx="22" ry="7" fill="#6EE7B7"/>
            </svg>
        </div>

        <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-white/10 rounded-full pointer-events-none"></div>
    </div>

    <!-- 4 High-Level Key Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Pembiayaan Neto -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">
                    Total Aset Pembiayaan
                </span>
                <span class="text-xs font-bold text-slate-400">{{ $totalAccounts }} Akad</span>
            </div>
            <div class="mt-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-brand-muted">Nilai Tercatat Neto</p>
                <h3 class="text-xl font-extrabold text-brand-navy font-jakarta mt-0.5">Rp {{ number_format($totalNetCarrying, 0, ',', '.') }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Plafon Bruto</span>
                <span class="font-bold text-slate-700">Rp {{ number_format($totalFinancing, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Total CKPN Syariah (ECL) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-brand-blue border border-blue-200 uppercase tracking-wider">
                    Total CKPN Syariah
                </span>
                <span class="text-xs font-bold text-brand-blue">PSAK 413</span>
            </div>
            <div class="mt-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-brand-muted">Ekspektasi Kerugian (ECL)</p>
                <h3 class="text-xl font-extrabold text-brand-navy font-jakarta mt-0.5">Rp {{ number_format($totalEcl, 0, ',', '.') }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Coverage Ratio</span>
                <span class="font-extrabold text-brand-blue">{{ number_format($coverageRatio, 1, ',', '.') }}%</span>
            </div>
        </div>

        <!-- Provisi Kafalah -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200 uppercase tracking-wider">
                    Provisi Kafalah
                </span>
                <span class="text-xs font-bold text-purple-600">Penjaminan</span>
            </div>
            <div class="mt-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-brand-muted">Kewajiban Kontinjensi</p>
                <h3 class="text-xl font-extrabold text-brand-navy font-jakarta mt-0.5">Rp {{ number_format($totalKafalahProvision, 0, ',', '.') }}</h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Nilai Jaminan</span>
                <span class="font-bold text-slate-700">Rp {{ number_format($totalKafalahGuarantee, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- NPF Ratio (Non-Performing Financing) -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold {{ $npfGross <= 3.0 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }} border uppercase tracking-wider">
                    NPF Syariah
                </span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $npfGross <= 5.0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $npfGross <= 5.0 ? 'Sehat (OJK)' : 'Perhatian' }}
                </span>
            </div>
            <div class="mt-4">
                <p class="text-[11px] font-bold uppercase tracking-wider text-brand-muted">NPF Gross / Net</p>
                <h3 class="text-xl font-extrabold text-brand-navy font-jakarta mt-0.5">{{ number_format($npfGross, 2, ',', '.') }}% <span class="text-xs font-semibold text-slate-400">/ {{ number_format($npfNet, 2, ',', '.') }}%</span></h3>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">Nominal NPF (Stage 3)</span>
                <span class="font-extrabold text-rose-600">Rp {{ number_format($stage3Amount, 0, ',', '.') }}</span>
            </div>
        </div>

    </div>

    <!-- 3-Staging Breakdown Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stage 1 -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-extrabold text-emerald-800 uppercase tracking-wider">Stage 1 • Performing</span>
                </div>
                <p class="text-lg font-extrabold text-brand-navy mt-2">Rp {{ number_format($stage1Amount, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-0.5">{{ $stage1Count }} Nasabah • CKPN: Rp {{ number_format($stage1Ecl, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-extrabold text-sm">
                {{ ($totalNetCarrying > 0) ? round(($stage1Amount / $totalNetCarrying) * 100, 1) : 0 }}%
            </div>
        </div>

        <!-- Stage 2 -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <span class="text-xs font-extrabold text-amber-800 uppercase tracking-wider">Stage 2 • SICR</span>
                </div>
                <p class="text-lg font-extrabold text-brand-navy mt-2">Rp {{ number_format($stage2Amount, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-0.5">{{ $stage2Count }} Nasabah • CKPN: Rp {{ number_format($stage2Ecl, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-extrabold text-sm">
                {{ ($totalNetCarrying > 0) ? round(($stage2Amount / $totalNetCarrying) * 100, 1) : 0 }}%
            </div>
        </div>

        <!-- Stage 3 -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    <span class="text-xs font-extrabold text-rose-800 uppercase tracking-wider">Stage 3 • Impaired (NPF)</span>
                </div>
                <p class="text-lg font-extrabold text-brand-navy mt-2">Rp {{ number_format($stage3Amount, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-0.5">{{ $stage3Count }} Nasabah • CKPN: Rp {{ number_format($stage3Ecl, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-extrabold text-sm">
                {{ ($totalNetCarrying > 0) ? round(($stage3Amount / $totalNetCarrying) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Donut Chart: Komposisi Akad Syariah -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-soft space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-brand-navy font-jakarta">Distribusi Akad Syariah</h3>
                    <p class="text-xs text-brand-muted mt-0.5">Komposisi portofolio per akad pembiayaan</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">PSAK 413</span>
            </div>
            <div class="relative h-64 flex items-center justify-center">
                <canvas id="shariaContractDonutChart"></canvas>
            </div>
        </div>

        <!-- Bar Chart: Staging & ECL per Akad -->
        <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-soft space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-brand-navy font-jakarta">Ekspektasi Kerugian (ECL) &amp; NPF per Akad</h3>
                    <p class="text-xs text-brand-muted mt-0.5">Perbandingan nominal eksposur neto vs CKPN cadangan</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-semibold">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Neto
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-semibold">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> CKPN
                    </span>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="shariaEclBarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Filter & Portfolios Table -->
    <div class="bg-white rounded-3xl border border-slate-100/80 shadow-soft overflow-hidden space-y-6 p-6 sm:p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div>
                <h3 class="text-lg font-extrabold text-brand-navy font-jakarta">Portofolio Pembiayaan Syariah &amp; Detail Staging</h3>
                <p class="text-xs text-brand-muted mt-0.5">Daftar fasilitas nasabah dengan kalkulasi parameter PSAK 413 secara individual</p>
            </div>

            <!-- Filter Controls -->
            <form action="{{ route('psak413.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <!-- Search -->
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari no rekening, nasabah..." 
                       class="px-4 py-2 text-xs bg-[#F5F7FA] border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-slate-800">

                <!-- Contract Filter -->
                <select name="contract_type" onchange="this.form.submit()" class="px-3.5 py-2 text-xs bg-[#F5F7FA] border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-slate-800 font-semibold">
                    <option value="ALL" {{ $contractFilter == 'ALL' ? 'selected' : '' }}>Semua Akad</option>
                    <option value="MURABAHAH" {{ $contractFilter == 'MURABAHAH' ? 'selected' : '' }}>Murabahah</option>
                    <option value="MUSYARAKAH" {{ $contractFilter == 'MUSYARAKAH' ? 'selected' : '' }}>Musyarakah</option>
                    <option value="MUDHARABAH" {{ $contractFilter == 'MUDHARABAH' ? 'selected' : '' }}>Mudharabah</option>
                    <option value="IJARAH" {{ $contractFilter == 'IJARAH' ? 'selected' : '' }}>Ijarah</option>
                    <option value="ISTISHNA" {{ $contractFilter == 'ISTISHNA' ? 'selected' : '' }}>Istishna'</option>
                    <option value="QARDH" {{ $contractFilter == 'QARDH' ? 'selected' : '' }}>Qardh</option>
                    <option value="KAFALAH" {{ $contractFilter == 'KAFALAH' ? 'selected' : '' }}>Kafalah</option>
                </select>

                <!-- Stage Filter -->
                <select name="stage" onchange="this.form.submit()" class="px-3.5 py-2 text-xs bg-[#F5F7FA] border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-slate-800 font-semibold">
                    <option value="ALL" {{ $stageFilter == 'ALL' ? 'selected' : '' }}>Semua Stage</option>
                    <option value="1" {{ $stageFilter == '1' ? 'selected' : '' }}>Stage 1 (Performing)</option>
                    <option value="2" {{ $stageFilter == '2' ? 'selected' : '' }}>Stage 2 (SICR)</option>
                    <option value="3" {{ $stageFilter == '3' ? 'selected' : '' }}>Stage 3 (Impaired)</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-all">
                    Filter
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-extrabold uppercase tracking-wider text-brand-muted">
                        <th class="py-3 px-3">No Rekening / Nasabah</th>
                        <th class="py-3 px-3">Akad &amp; Sektor</th>
                        <th class="py-3 px-3 text-right">Nilai Neto (EAD)</th>
                        <th class="py-3 px-3 text-center">DPD</th>
                        <th class="py-3 px-3 text-center">Staging</th>
                        <th class="py-3 px-3 text-right">PD / LGD</th>
                        <th class="py-3 px-3 text-right">CKPN Syariah</th>
                        <th class="py-3 px-3 text-right">Provisi Kafalah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs">
                    @forelse($portfolios as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-3">
                                <p class="font-bold text-brand-navy">{{ $p->customer_name }}</p>
                                <p class="font-mono text-[10px] text-slate-400">{{ $p->account_number }}</p>
                            </td>
                            <td class="py-3.5 px-3">
                                @php
                                    $badgeColor = match($p->contract_type) {
                                        'MURABAHAH' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'MUSYARAKAH' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'MUDHARABAH' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'IJARAH' => 'bg-teal-50 text-teal-700 border-teal-200',
                                        'ISTISHNA' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'QARDH' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'KAFALAH' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeColor }}">
                                    {{ $p->contract_type }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1 truncate max-w-[140px]">{{ $p->sector }}</p>
                            </td>
                            <td class="py-3.5 px-3 text-right">
                                <p class="font-bold text-slate-800">Rp {{ number_format($p->net_carrying_amount, 0, ',', '.') }}</p>
                                @if($p->margin_suspended > 0)
                                    <p class="text-[10px] text-slate-400">Margin: -Rp {{ number_format($p->margin_suspended, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <span class="font-mono font-bold {{ $p->dpd > 90 ? 'text-rose-600' : ($p->dpd > 30 ? 'text-amber-600' : 'text-slate-600') }}">
                                    {{ $p->dpd }} Hari
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                @if($p->stage == 1)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Stage 1
                                    </span>
                                @elseif($p->stage == 2)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        Stage 2
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                        Stage 3
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-right font-mono text-[11px]">
                                <span class="text-slate-700 font-bold">{{ number_format($p->pd_rate, 2) }}%</span> / 
                                <span class="text-slate-500">{{ number_format($p->lgd_rate, 2) }}%</span>
                            </td>
                            <td class="py-3.5 px-3 text-right">
                                <span class="font-extrabold text-emerald-700">Rp {{ number_format($p->ecl_allowance, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-3.5 px-3 text-right">
                                @if($p->kafalah_provision_amount > 0)
                                    <span class="font-bold text-purple-700">Rp {{ number_format($p->kafalah_provision_amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400 font-medium">
                                Tidak ada data portofolio pembiayaan syariah yang cocok dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            {{ $portfolios->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Sharia Contract Donut Chart
        const donutCtx = document.getElementById('shariaContractDonutChart');
        if (donutCtx) {
            const contractData = @json($contractSummary);
            const labels = contractData.map(c => c.contract_type);
            const values = contractData.map(c => c.total_net);

            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#2D60FF', '#10B981', '#F59E0B', '#14B8A6', '#6366F1', '#94A3B8', '#A855F7'
                        ],
                        borderWidth: 2,
                        borderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 10, family: 'Inter' },
                                boxWidth: 10,
                                padding: 8
                            }
                        }
                    },
                    cutout: '68%'
                }
            });
        }

        // 2. Sharia ECL vs Net Bar Chart
        const barCtx = document.getElementById('shariaEclBarChart');
        if (barCtx) {
            const contractData = @json($contractSummary);
            const labels = contractData.map(c => c.contract_type);
            const netValues = contractData.map(c => c.total_net);
            const eclValues = contractData.map(c => c.total_ecl);

            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Neto Pembiayaan',
                            data: netValues,
                            backgroundColor: '#10B981',
                            borderRadius: 6
                        },
                        {
                            label: 'CKPN Cadangan',
                            data: eclValues,
                            backgroundColor: '#EF4444',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            ticks: {
                                callback: function(val) {
                                    return (val / 1000000000).toFixed(1) + ' M';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection

