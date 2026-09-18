@extends('layouts.app')

@section('title', 'Investasi & Treasury')
@section('header_title', 'Portofolio Treasury & Investasi Bank')

@section('content')
<div class="space-y-8">

    <!-- KPI Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Total Modal Ditempatkan</p>
                <h3 class="text-2xl font-extrabold text-brand-navy font-jakarta mt-1">Rp {{ number_format($totalInvested, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">SBN, Sukuk & Pasar Uang</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Nilai Pasar Saat Ini</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 font-jakarta mt-1">Rp {{ number_format($totalCurrentValue, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Mark to market terkini</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Keuntungan Bersih (Gain)</p>
                <h3 class="text-2xl font-extrabold text-brand-blue font-jakarta mt-1">+Rp {{ number_format($totalGain, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">+{{ number_format($gainPercentage, 2) }}% RoI</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-brand-purple flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100/80 shadow-soft flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-brand-muted uppercase tracking-wider">Profil Risiko Portofolio</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 font-jakarta mt-1">Sovereign AAA</h3>
                <p class="text-[11px] text-slate-400 mt-1">Jaminan penuh Pemerintah RI</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Investments Table -->
    <div class="bg-white rounded-3xl border border-slate-100/80 shadow-soft overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Instrumen Treasury & Sekuritas</h3>
                <p class="text-xs text-brand-muted mt-0.5">Penempatan likuiditas pada instrumen berpenghasilan tetap</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">Kode Seri</th>
                        <th class="px-6 py-4">Nama Aset / Sekuritas</th>
                        <th class="px-6 py-4">Tipe Aset</th>
                        <th class="px-6 py-4 text-right">Modal Pokok</th>
                        <th class="px-6 py-4 text-right">Nilai Saat Ini</th>
                        <th class="px-6 py-4 text-right">Imbal Hasil (Yield)</th>
                        <th class="px-6 py-4 text-center">Tingkat Risiko</th>
                        <th class="px-6 py-4 text-center">Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($investments as $inv)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-brand-blue">{{ $inv->asset_code }}</td>
                            <td class="px-6 py-4 font-bold text-brand-navy">{{ $inv->asset_name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $inv->asset_type }}</td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($inv->principal_invested, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">Rp {{ number_format($inv->current_value, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-brand-blue">{{ $inv->return_percentage }}% p.a</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $inv->risk_level }} Risk
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-500 font-medium">
                                {{ $inv->maturity_date ? $inv->maturity_date->format('d/m/Y') : 'Open Ended' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

