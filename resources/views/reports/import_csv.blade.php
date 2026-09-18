@extends('layouts.app')

@section('title', 'Import Transaksi CSV / Excel')
@section('header_title', 'Import Data Bank via CSV / Excel')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Top Action Card: Download Template Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-700 via-brand-blue to-indigo-800 rounded-3xl p-8 text-white shadow-card flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 z-10 max-w-xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-blue-100 text-xs font-semibold backdrop-blur-sm">
                <!-- Excel file icon SVG -->
                <svg class="w-4 h-4 text-emerald-300" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/>
                </svg>
                <span>Format Standar Microsoft Excel (.csv)</span>
            </div>
            <h2 class="text-2xl font-extrabold font-jakarta tracking-tight">Format Template Transaksi Siap Pakai</h2>
            <p class="text-sm text-blue-100/90 leading-relaxed">
                Unduh file template CSV yang telah disesuaikan dengan Microsoft Excel. Anda cukup membuka file di Excel, mengisi transaksi harian, lalu mengunggahnya ke sistem.
            </p>
        </div>

        <div class="z-10 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-shrink-0">
            <a href="{{ route('csv.download_template_excel') }}" 
               class="inline-flex items-center justify-center gap-2.5 px-5 py-3.5 bg-white text-brand-blue hover:bg-blue-50 font-bold rounded-2xl shadow-lg transition-all active:scale-95 text-xs">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Download Template Excel (.xlsx)</span>
            </a>
            <a href="{{ route('csv.download_template') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-3.5 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-2xl backdrop-blur-sm transition-all active:scale-95 text-xs border border-white/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Format CSV</span>
            </a>
        </div>

        <!-- Decorative background circle -->
        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-white/10 rounded-full pointer-events-none"></div>
    </div>

    <!-- Main Grid: Upload Form + Instructions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Upload Form (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-8 border border-slate-100/80 shadow-soft space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-brand-navy font-jakarta">Formulir Upload File Transaksi</h3>
                <p class="text-xs text-brand-muted mt-1">Mendukung pemisah koma (,) maupun titik-koma (;) otomatis dari Excel</p>
            </div>

            <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Target Account Dropdown (Optional fallback) -->
                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">
                        Pilih Rekening Tujuan (Opsional)
                    </label>
                    <div class="relative">
                        <select name="target_account_id" 
                                class="w-full bg-[#F5F7FA] border border-slate-200 text-sm font-medium text-slate-800 rounded-2xl px-4 py-3 appearance-none focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue transition-all">
                            <option value="">-- Gunakan Nomor Rekening dari Isi File CSV (Otomatis) --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->account_number }} - {{ $acc->account_name }} ({{ $acc->account_type }})</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Jika baris di CSV tidak memuat no rekening, sistem akan memasukkannya ke rekening yang dipilih di atas.</p>
                </div>

                <!-- Drag and Drop Upload Area -->
                <div>
                    <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-2">
                        Pilih File CSV Hasil Excel
                    </label>

                    <div id="dropZone" class="relative border-2 border-dashed border-slate-200 hover:border-brand-blue/60 rounded-3xl p-8 text-center transition-all bg-[#F8FAFC] group cursor-pointer">
                        <input type="file" name="csv_file" id="csvFileInput" accept=".xlsx, .xls, .csv, .txt" required 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               onchange="handleFileSelected(this)">

                        <div class="space-y-3">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 group-hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition-colors shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-brand-navy" id="fileLabel">Klik untuk memilih file Excel (.xlsx / .csv) atau tarik ke sini</p>
                                <p class="text-xs text-brand-muted mt-0.5">Mendukung file .xlsx, .xls, atau .csv dari Microsoft Excel</p>
                            </div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-200/60 text-slate-600 text-[11px] font-semibold">
                                <span>Contoh nama: transaksi_september_2026.csv</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-4 pt-2">
                    <button type="reset" class="px-6 py-3 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-2xl shadow-md shadow-emerald-600/30 flex items-center gap-2 transition-all active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        <span>Mulai Proses Import Data</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions & Column Mapping Guide (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Step Guide Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft space-y-4">
                <h3 class="text-base font-bold text-brand-navy font-jakarta flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Langkah Mudah Pengisian di Excel</span>
                </h3>

                <div class="space-y-3.5 text-xs text-slate-600 leading-relaxed">
                    <div class="flex gap-3 items-start">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-brand-blue font-bold flex items-center justify-center flex-shrink-0 text-[11px]">1</span>
                        <p>Klik tombol <strong>Download Template Excel</strong> di atas dan buka file dengan Microsoft Excel.</p>
                    </div>
                    <div class="flex gap-3 items-start">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-brand-blue font-bold flex items-center justify-center flex-shrink-0 text-[11px]">2</span>
                        <p>Isi baris transaksi perbankan. Kolom wajib: <strong>Tanggal, Tipe (credit/debit), Nominal, Keterangan</strong>.</p>
                    </div>
                    <div class="flex gap-3 items-start">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-brand-blue font-bold flex items-center justify-center flex-shrink-0 text-[11px]">3</span>
                        <p>Di Excel, klik <em>File &gt; Save As</em> dan pilih format <strong>CSV (Comma delimited) (*.csv)</strong>.</p>
                    </div>
                    <div class="flex gap-3 items-start">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-brand-blue font-bold flex items-center justify-center flex-shrink-0 text-[11px]">4</span>
                        <p>Unggah file tersebut di form sebelah kiri. Sistem otomatis memperbarui saldo buku kas & laporan.</p>
                    </div>
                </div>
            </div>

            <!-- Table Structure Preview Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-brand-muted">Struktur Kolom CSV Template</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-[11px] text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500 font-semibold">
                                <th class="py-2">Nama Kolom</th>
                                <th class="py-2">Tipe</th>
                                <th class="py-2">Contoh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <tr>
                                <td class="py-2 font-mono font-medium text-brand-blue">Tanggal</td>
                                <td class="py-2 text-slate-500">YYYY-MM-DD</td>
                                <td class="py-2">2026-09-18</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-mono font-medium text-brand-blue">No Rekening</td>
                                <td class="py-2 text-slate-500">String</td>
                                <td class="py-2">1029-3847-5612</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-mono font-medium text-brand-blue">Tipe</td>
                                <td class="py-2 text-slate-500">credit / debit</td>
                                <td class="py-2">credit (masuk)</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-mono font-medium text-brand-blue">Kategori</td>
                                <td class="py-2 text-slate-500">String</td>
                                <td class="py-2">Deposit / Bill</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-mono font-medium text-brand-blue">Nominal</td>
                                <td class="py-2 text-slate-500">Angka murni</td>
                                <td class="py-2">15000000</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-mono font-medium text-brand-blue">Keterangan</td>
                                <td class="py-2 text-slate-500">Text</td>
                                <td class="py-2">Setoran Modal Usaha</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Upload Logs -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100/80 shadow-soft space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-brand-muted">Riwayat Upload Terakhir</h4>
                <div class="space-y-2">
                    @forelse($recentUploadLogs as $log)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                            <div class="flex items-center justify-between text-slate-400 text-[10px]">
                                <span>{{ $log->created_at->diffForHumans() }}</span>
                                <span>{{ $log->ip_address }}</span>
                            </div>
                            <p class="text-slate-700 font-medium mt-1">{{ $log->details }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada file yang diupload hari ini.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function handleFileSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const label = document.getElementById('fileLabel');
            label.innerHTML = '<span class="text-emerald-600 font-bold">' + file.name + '</span> (' + (file.size / 1024).toFixed(1) + ' KB) terpilih';
            document.getElementById('dropZone').classList.add('border-emerald-500', 'bg-emerald-50/30');
        }
    }
</script>
@endpush

