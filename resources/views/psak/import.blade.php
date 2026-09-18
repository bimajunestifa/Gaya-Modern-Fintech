@extends('layouts.app')

@section('title', 'Batch Import Kredit PSAK 71')
@section('header_title', 'Batch Import & Otomasi Hitung PSAK 71 (Excel)')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Top Action Card: Download Template Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-3xl p-8 text-white shadow-card flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 z-10 max-w-xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-emerald-100 text-xs font-semibold backdrop-blur-sm">
                <svg class="w-4 h-4 text-emerald-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
                </svg>
                <span>Format Standar Microsoft Excel (.xlsx / .csv) &amp; APOLO OJK</span>
            </div>
            <h2 class="text-2xl font-extrabold font-jakarta tracking-tight">Impor Massal Debitur &amp; Hitung CKPN Seketika</h2>
            <p class="text-xs text-emerald-100/90 leading-relaxed">
                Unduh template spreadsheet Excel atau gunakan file ekspor portofolio kredit APOLO OJK Anda. Sistem secara otomatis mendeteksi kolom, mengklasifikasikan Staging 1/2/3, menghitung nilai CKPN, dan membukukan jurnal akuntansi secara terpadu.
            </p>
        </div>

        <div class="z-10 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-shrink-0">
            <!-- Download XLSX -->
            <a href="{{ route('psak.download_template_excel') }}" 
               class="inline-flex items-center justify-center gap-2.5 px-5 py-3.5 bg-white text-emerald-800 hover:bg-emerald-50 font-bold rounded-2xl shadow-lg transition-all active:scale-95 text-xs">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Download Template Excel (.xlsx)</span>
            </a>

            <!-- Download CSV -->
            <a href="{{ route('psak.download_template') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-3.5 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-2xl backdrop-blur-sm transition-all active:scale-95 text-xs border border-white/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Format CSV</span>
            </a>
        </div>

        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-white/10 rounded-full pointer-events-none"></div>
    </div>

    <!-- Upload Form Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Upload Box (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-8 border border-slate-100/80 shadow-soft space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Formulir Upload File Portofolio Kredit</h3>
                <p class="text-xs text-brand-muted mt-0.5">Mendukung format file <b>.xlsx, .xls, .csv</b> langsung dari Microsoft Excel atau ekspor Core Banking</p>
            </div>

            <form action="{{ route('psak.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Dropzone -->
                <div id="dropZonePsak" class="relative border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-3xl p-8 text-center transition-all bg-[#F8FAFC] group cursor-pointer">
                    <input type="file" name="csv_file" id="psakFile" accept=".xlsx, .xls, .csv, .txt" required 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           onchange="handlePsakFile(this)">

                    <div class="space-y-3">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 group-hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-brand-navy" id="psakFileLabel">Klik untuk memilih file Excel (.xlsx / .xls) atau seret ke sini</p>
                            <p class="text-xs text-brand-muted mt-0.5">File picker Windows akan menampilkan file Excel (.xlsx / .xls / .csv) Anda</p>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-200/60 text-slate-600 text-[11px] font-semibold">
                            <span>Auto Engine: Klasifikasi Stage 1, Stage 2 (SICR), &amp; Stage 3 (Macet)</span>
                        </div>
                    </div>
                </div>

                <!-- Import Mode Selection -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                    <span class="text-xs font-bold text-slate-700 block">Pilihan Mode Impor:</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="relative flex items-start gap-3 p-3 rounded-xl border border-emerald-300 bg-emerald-50/50 cursor-pointer hover:bg-emerald-50 transition-all">
                            <input type="radio" name="import_mode" value="replace" checked class="mt-1 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <p class="text-xs font-bold text-emerald-900">Ganti Portofolio (Replace)</p>
                                <p class="text-[11px] text-emerald-700 mt-0.5 leading-snug">Hapus data lama &amp; hitung murni dari Excel baru ini (Direkomendasikan)</p>
                            </div>
                        </label>
                        <label class="relative flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:bg-slate-50 transition-all">
                            <input type="radio" name="import_mode" value="append" class="mt-1 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <p class="text-xs font-bold text-slate-800">Tambahkan Data (Append)</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Gabungkan data Excel ini dengan data portofolio yang ada di database</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-xs text-slate-400">Maksimum ukuran file: 20 MB</span>
                    <button type="submit" class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl shadow-md shadow-emerald-600/30 flex items-center gap-2 transition-all active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        <span>Proses Data &amp; Hitung CKPN PSAK 71</span>
                    </button>
                </div>
            </form>

            <!-- APOLO Mapping Info Box -->
            <div class="p-5 rounded-2xl bg-blue-50/60 border border-blue-100 text-xs space-y-2.5">
                <div class="flex items-center gap-2 text-brand-blue font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Pemetaan Otomatis Kolom APOLO OJK (Smart Header Detection)</span>
                </div>
                <p class="text-slate-600 leading-relaxed text-[11px]">
                    Jika Anda memiliki file Excel dari <b>APOLO OJK Form 01/02</b> atau <b>ETL Core Banking</b>, Anda dapat langsung mengunggahnya! Sistem pintar akan mencocokkan header kolom secara otomatis:
                </p>
                <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-700 pt-1">
                    <div class="p-2 rounded-xl bg-white border border-blue-100/60">
                        <b class="text-brand-blue">No Rekening:</b> <code>NO_REK</code>, <code>CIF</code>, <code>NO_AKAD</code>, <code>NO_FASILITAS</code>
                    </div>
                    <div class="p-2 rounded-xl bg-white border border-blue-100/60">
                        <b class="text-brand-blue">Baki Debet:</b> <code>BAKI_DEBET</code>, <code>OUTSTANDING</code>, <code>EAD</code>
                    </div>
                    <div class="p-2 rounded-xl bg-white border border-blue-100/60">
                        <b class="text-brand-blue">Kolektibilitas:</b> <code>KOL 1-5</code> (Otomatis konversi ke Stage &amp; DPD)
                    </div>
                    <div class="p-2 rounded-xl bg-white border border-blue-100/60">
                        <b class="text-brand-blue">Agunan:</b> <code>NILAI_AGUNAN</code>, <code>JAMINAN</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions & Column Mapping (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-brand-navy">Struktur Kolom Template</h4>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold">11 Kolom</span>
                </div>
                <div class="space-y-1.5 text-xs">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">1. No Fasilitas Kredit</span>
                        <span class="text-slate-400 font-mono">Kode Unik Fasilitas</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">2. Nama Debitur</span>
                        <span class="text-slate-400">Nama Perusahaan/Debitur</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">3. Jenis Fasilitas</span>
                        <span class="text-slate-400">Modal Kerja / Investasi</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">4. Sektor Ekonomi</span>
                        <span class="text-slate-400">Agribisnis, Tambang, dll</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">5. Plafon Kredit</span>
                        <span class="text-slate-400 font-mono">Nominal Plafon Awal</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">6. Baki Debet (EAD)</span>
                        <span class="text-slate-400 font-mono">Outstanding Pokok</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">7. Nilai Agunan</span>
                        <span class="text-slate-400 font-mono">Kolateral Terdaftar</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">8. DPD (Hari Tunggakan)</span>
                        <span class="text-slate-400 font-mono">&gt;90 hari = Stage 3</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">9. Restrukturisasi</span>
                        <span class="text-slate-400 font-mono">YA / TIDAK (SICR)</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex justify-between">
                        <span class="font-bold text-slate-700">10. Kolektibilitas OJK</span>
                        <span class="text-slate-400 font-mono">Kol 1 s/d Kol 5</span>
                    </div>
                </div>
            </div>

            <!-- Current Summary Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft text-xs space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-brand-muted">Portofolio Saat Ini Terdata</h4>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 font-mono text-[10px] font-bold">{{ $portfoliosCount }} Data</span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Total Fasilitas Aktif:</span>
                        <span class="font-bold text-brand-navy">{{ $portfoliosCount }} Debitur</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Total Baki Debet (EAD):</span>
                        <span class="font-bold text-brand-navy font-mono">Rp {{ number_format($totalEad, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 text-emerald-600 font-bold">
                        <span>Total Cadangan CKPN Terbentuk:</span>
                        <span class="font-mono">Rp {{ number_format($totalEcl, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($portfoliosCount > 0)
                <form action="{{ route('psak.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan seluruh portofolio kredit PSAK 71 saat ini?')" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold text-xs flex items-center justify-center gap-2 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Kosongkan / Reset Portofolio</span>
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function handlePsakFile(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const label = document.getElementById('psakFileLabel');
            label.innerHTML = '<span class="text-emerald-600 font-bold">' + file.name + '</span> (' + (file.size / 1024).toFixed(1) + ' KB) siap dihitung';
            document.getElementById('dropZonePsak').classList.add('border-emerald-500', 'bg-emerald-50/40');
        }
    }
</script>
@endpush
