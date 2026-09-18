@extends('layouts.app')

@section('title', 'Laporan Keuangan Neraca & Laba Rugi PSAK')
@section('header_title', 'Laporan Keuangan Publikasi Standar PSAK / OJK')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Action Bar (Hidden when printing) -->
    <div class="no-print bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-brand-navy font-jakarta">Laporan Keuangan Bank Standar PSAK 1 & OJK</h2>
            <p class="text-xs text-brand-muted">Laporan Posisi Keuangan (Neraca), Laba Rugi Komprehensif, dan Rasio Keuangan Utama</p>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-6 py-2.5 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-md transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Publikasi / PDF</span>
            </button>
            <a href="{{ route('psak.dashboard') }}" class="px-5 py-2.5 bg-brand-blue hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all">
                Dashboard PSAK 71 &rarr;
            </a>
        </div>
    </div>

    <!-- Key Banking Ratios Grid (OJK Standard) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold uppercase text-brand-muted">CAR (Kecukupan Modal)</p>
            <p class="text-xl font-extrabold text-brand-navy font-jakarta mt-1">{{ number_format($car, 2) }}%</p>
            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Min OJK: 12.0%</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold uppercase text-brand-muted">NPL Gross</p>
            <p class="text-xl font-extrabold text-amber-600 font-jakarta mt-1">{{ number_format($nplGross, 2) }}%</p>
            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Batas Aman &lt; 5.0%</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold uppercase text-brand-muted">NPL Netto (Setelah CKPN)</p>
            <p class="text-xl font-extrabold text-emerald-600 font-jakarta mt-1">{{ number_format($nplNet, 2) }}%</p>
            <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Kualitas Prima</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold uppercase text-brand-muted">NIM (Net Interest Margin)</p>
            <p class="text-xl font-extrabold text-brand-blue font-jakarta mt-1">{{ number_format($nim, 2) }}%</p>
            <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Margin Bunga Bersih</p>
        </div>
    </div>

    <!-- Official Printable Report Sheet -->
    <div class="bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-soft text-slate-800 space-y-8 print:shadow-none print:border-none print:p-0">
        
        <!-- Header Bank Letterhead -->
        <div class="text-center border-b-2 border-slate-900 pb-6 space-y-1">
            <h1 class="text-xl font-black tracking-tight text-slate-900 font-jakarta uppercase">BANK CENTRAL DIGITAL INDONESIA TBK</h1>
            <h2 class="text-sm font-extrabold text-slate-700 font-jakarta">LAPORAN POSISI KEUANGAN & LABA RUGI KOMPREHENSIF PUBLIKASI</h2>
            <p class="text-xs text-slate-500">Per Tanggal 30 September 2026 (Sesuai Standar Akuntansi Keuangan PSAK & Regulasi OJK)</p>
            <p class="text-[11px] text-slate-400 italic">(Dinyatakan dalam Rupiah penuh, kecuali dinyatakan lain)</p>
        </div>

        <!-- 1. Laporan Posisi Keuangan (Neraca) -->
        <div class="space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider bg-slate-100 p-2.5 rounded-lg">
                I. LAPORAN POSISI KEUANGAN (NERACA)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-xs">
                <!-- Aset -->
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-900 border-b border-slate-200 pb-1 uppercase tracking-wider text-[11px]">ASET</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span>1. Kas & Setara Kas</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($kasBank, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span>2. Penempatan pada Bank Indonesia</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($penempatanBi, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span>3. Surat Berharga Dimiliki (SBN & Sukuk)</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($suratBerharga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span>4. Kredit yang Diberikan (Gross)</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($kreditGross, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 text-brand-rose">
                            <span>&nbsp;&nbsp;&nbsp;Dikurangi: Cadangan CKPN (PSAK 71)</span>
                            <span class="font-mono font-bold">(Rp {{ number_format($ckpnKredit, 0, ',', '.') }})</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 bg-blue-50/50 px-2 rounded">
                            <span class="font-semibold">Kredit yang Diberikan (Netto)</span>
                            <span class="font-mono font-bold text-brand-blue">Rp {{ number_format($kreditNetto, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span>5. Aset Tetap & Hak Guna (PSAK 73)</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($asetTetap, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span>6. Aset Lain-lain</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($asetLainnya, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-t-2 border-slate-900 font-extrabold text-sm text-slate-900">
                            <span>TOTAL ASET</span>
                            <span class="font-mono">Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Liabilitas & Ekuitas -->
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-900 border-b border-slate-200 pb-1 uppercase tracking-wider text-[11px]">LIABILITAS & EKUITAS</h4>
                    <div class="space-y-2">
                        <p class="font-semibold text-slate-700 text-[11px] pt-1">LIABILITAS:</p>
                        <div class="flex justify-between py-1 border-b border-slate-100 pl-3">
                            <span>1. Giro Nasabah</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($dpkGiro, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 pl-3">
                            <span>2. Tabungan</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($dpkTabungan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 pl-3">
                            <span>3. Simpanan Berjangka (Deposito)</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($dpkDeposito, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 pl-3">
                            <span>4. Liabilitas Lain-lain</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($liabilitasLain, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-200 font-bold text-slate-800">
                            <span>Total Liabilitas</span>
                            <span class="font-mono">Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</span>
                        </div>

                        <p class="font-semibold text-slate-700 text-[11px] pt-2">EKUITAS:</p>
                        <div class="flex justify-between py-1 border-b border-slate-100 pl-3">
                            <span>1. Modal Disetor / Modal Inti Tier 1</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($modalInti, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 pl-3">
                            <span>2. Cadangan Umum & Tambahan</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($cadanganUmum, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 pl-3">
                            <span>3. Saldo Laba (Retained Earnings)</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($labaDitahan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-200 font-bold text-slate-800">
                            <span>Total Ekuitas</span>
                            <span class="font-mono">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between py-2 border-t-2 border-slate-900 font-extrabold text-sm text-slate-900">
                            <span>TOTAL LIABILITAS & EKUITAS</span>
                            <span class="font-mono">Rp {{ number_format($totalLiabilitas + $totalEkuitas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Laporan Laba Rugi Komprehensif -->
        <div class="space-y-4 pt-4 border-t border-slate-200">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider bg-slate-100 p-2.5 rounded-lg">
                II. LAPORAN LABA RUGI & PENGHASILAN KOMPREHENSIF LAIN
            </h3>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="font-medium">Pendapatan Bunga dan Imbal Hasil</span>
                    <span class="font-mono font-semibold">Rp {{ number_format($pendapatanBunga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100 text-brand-rose">
                    <span class="font-medium">Beban Bunga</span>
                    <span class="font-mono font-semibold">(Rp {{ number_format($bebanBunga, 0, ',', '.') }})</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-200 font-bold text-slate-900 bg-slate-50 px-2 rounded">
                    <span>PENDAPATAN BUNGA BERSIH (NET INTEREST INCOME - NII)</span>
                    <span class="font-mono text-brand-blue">Rp {{ number_format($pendapatanBungaBersih, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100 text-brand-rose">
                    <span class="font-medium">Beban Pembentukan Cadangan Kerugian Penurunan Nilai (CKPN / ECL PSAK 71)</span>
                    <span class="font-mono font-bold">(Rp {{ number_format($bebanEclCkpn, 0, ',', '.') }})</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="font-medium">Pendapatan Operasional Lainnya (Fee Based Income)</span>
                    <span class="font-mono font-semibold">Rp {{ number_format($pendapatanOperasionalLain, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100 text-brand-rose">
                    <span class="font-medium">Beban Operasional Umum & Administrasi</span>
                    <span class="font-mono font-semibold">(Rp {{ number_format($bebanOperasional, 0, ',', '.') }})</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-200 font-bold text-slate-900">
                    <span>LABA SEBELUM PAJAK PENGHASILAN</span>
                    <span class="font-mono">Rp {{ number_format($labaOperasional, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100 text-slate-600">
                    <span>Estimasi Beban Pajak Penghasilan (22%)</span>
                    <span class="font-mono">(Rp {{ number_format($pajakPenghasilan, 0, ',', '.') }})</span>
                </div>
                <div class="flex justify-between py-2.5 border-t-2 border-slate-900 font-black text-sm text-emerald-700 bg-emerald-50/50 px-3 rounded-lg">
                    <span>LABA BERSIH PERIODE BERJALAN</span>
                    <span class="font-mono text-base">Rp {{ number_format($labaBersihTahunBerjalan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Authorized Signatures -->
        <div class="pt-8 grid grid-cols-2 gap-8 text-xs items-end">
            <div class="text-slate-500 text-[11px] leading-relaxed">
                <p class="font-bold text-slate-800">Pernyataan Direksi:</p>
                <p>Laporan keuangan ini telah disajikan secara wajar dalam semua hal yang material sesuai dengan Standar Akuntansi Keuangan di Indonesia (PSAK 71, PSAK 68, PSAK 1) dan Peraturan Otoritas Jasa Keuangan (POJK).</p>
            </div>

            <div class="text-center space-y-12">
                <p class="text-xs text-slate-600 font-semibold">Jakarta, 30 September 2026<br>Direksi PT Bank Central Digital Indonesia Tbk</p>
                <div>
                    <p class="font-bold text-slate-900 underline">Eddy Cusuma, S.E., M.M.</p>
                    <p class="text-[11px] text-slate-500">Direktur Utama & Kepatuhan</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

