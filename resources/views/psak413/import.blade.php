@extends('layouts.app')

@section('title', 'PSAK 413: Batch Import Portofolio Syariah')
@section('header_title', 'PSAK 413: Batch Excel & CSV Importer')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-800 to-teal-900 rounded-3xl p-8 text-white shadow-soft flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 max-w-xl">
            <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-white/20 text-emerald-100 text-xs font-bold">
                <span>Batch Data Integration • PSAK 413</span>
            </div>
            <h2 class="text-2xl font-extrabold font-jakarta">Unggah Massal Portofolio Pembiayaan Syariah</h2>
            <p class="text-xs text-emerald-100 leading-relaxed">
                Impor data ratusan fasilitas pembiayaan syariah dari core banking legacy. Sistem akan otomatis mengklasifikasikan akad, menghitung Staging 1/2/3, PD, LGD, dan nilai cadangan CKPN secara instan.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <a href="{{ route('psak413.download_template') }}" class="px-5 py-2.5 bg-white text-emerald-800 font-bold rounded-xl text-xs hover:bg-emerald-50 transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Unduh Template CSV / Excel</span>
            </a>
        </div>
    </div>

    <!-- Upload & Reset Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Left: Upload Form (2 Cols) -->
        <div class="md:col-span-2 bg-white p-8 rounded-3xl border border-slate-100 shadow-soft space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-extrabold text-brand-navy font-jakarta">Pilih File CSV / Excel Pembiayaan</h3>
                <p class="text-xs text-brand-muted mt-0.5">Format file yang didukung: .csv, .txt (pemisah titik-koma atau koma)</p>
            </div>

            <form action="{{ route('psak413.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Dropzone Area -->
                <div class="relative border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-2xl p-8 text-center transition-all bg-slate-50/50 cursor-pointer group">
                    <input type="file" name="file" id="fileInput" required accept=".csv,.txt" 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                           onchange="updateFileName(this)">
                    <div class="space-y-3 pointer-events-none">
                        <div class="w-14 h-14 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700" id="fileNameLabel">Seret &amp; letakkan file CSV/Excel di sini, atau klik untuk memilih</p>
                            <p class="text-xs text-slate-400 mt-1">Maksimal ukuran file: 10 MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('psak413.download_template') }}" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Download Contoh Data (.CSV)</span>
                    </a>

                    <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95">
                        Mulai Proses Batch Impor &rarr;
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Guide & Reset Action (1 Col) -->
        <div class="space-y-6">
            
            <!-- Quick Reset Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-soft space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-brand-navy">Reset Dataset Standar</h4>
                        <p class="text-[10px] text-brand-muted">Kembalikan portofolio ke acuan awal</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Menghapus data saat ini dan mengisi kembali 8 fasilitas pembiayaan contoh dengan beragam akad (Murabahah, Musyarakah, Mudharabah, Ijarah, Istishna', Qardh, Kafalah).
                </p>
                <form action="{{ route('psak413.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin me-reset portofolio ke data default?');">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-xs rounded-xl transition-colors">
                        Reset ke Data Default
                    </button>
                </form>
            </div>

            <!-- Structure Guide -->
            <div class="bg-emerald-50/50 p-6 rounded-3xl border border-emerald-100 space-y-3">
                <h4 class="text-xs font-extrabold text-emerald-900 uppercase tracking-wider">Struktur Kolom Wajib</h4>
                <ul class="text-[11px] text-emerald-800 space-y-1.5 list-disc pl-4">
                    <li><strong>Nomor Rekening:</strong> ID unik fasilitas</li>
                    <li><strong>Nama Nasabah:</strong> Nama entitas/debitur</li>
                    <li><strong>Jenis Akad:</strong> MURABAHAH, MUSYARAKAH, MUDHARABAH, IJARAH, ISTISHNA, QARDH, KAFALAH</li>
                    <li><strong>Plafon &amp; Saldo Pokok:</strong> Angka nominal</li>
                    <li><strong>Margin Ditangguhkan:</strong> Khusus jual-beli</li>
                    <li><strong>Agunan &amp; DPD:</strong> Nilai &amp; hari tunggakan</li>
                </ul>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
    function updateFileName(input) {
        const label = document.getElementById('fileNameLabel');
        if (input.files && input.files[0]) {
            label.innerText = 'File Terpilih: ' + input.files[0].name + ' (' + (input.files[0].size / 1024).toFixed(1) + ' KB)';
            label.className = 'text-sm font-bold text-emerald-700';
        }
    }
</script>
@endpush
@endsection

