@extends('layouts.app')

@section('title', 'CMS Warta & Pengumuman Bank')
@section('header_title', 'Content Management System (CMS)')

@section('content')
<div class="space-y-8">

    <!-- Top Action & Info -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-brand-navy font-jakarta">Pengumuman Resmi & Kebijakan Bank</h2>
            <p class="text-xs text-brand-muted mt-0.5">Kelola publikasi informasi suku bunga, regulasi kepatuhan OJK, dan buletin internal</p>
        </div>

        <button onclick="document.getElementById('modalNewArticle').classList.remove('hidden')" 
                class="inline-flex items-center gap-2 px-6 py-3 bg-brand-blue hover:bg-blue-700 text-white rounded-2xl text-xs font-bold shadow-md shadow-brand-blue/30 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tulis Pengumuman Baru</span>
        </button>
    </div>

    <!-- CMS Articles Table -->
    <div class="bg-white rounded-3xl border border-slate-100/80 shadow-soft overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-brand-navy font-jakarta">Daftar Publikasi Konten</h3>
            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                {{ $articles->total() }} Artikel Aktif
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">Judul Warta / Pengumuman</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Penulis / Divisi</th>
                        <th class="px-6 py-4">Tanggal Rilis</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($articles as $article)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 max-w-md">
                                <p class="font-bold text-brand-navy text-sm">{{ $article->title }}</p>
                                <p class="text-slate-500 mt-1 line-clamp-2">{{ $article->content }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-brand-blue">
                                    {{ $article->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium whitespace-nowrap">{{ $article->author }}</td>
                            <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                {{ $article->published_at ? $article->published_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Tayang</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <form action="{{ route('cms.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 p-1.5 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada publikasi pengumuman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 bg-[#F8FAFC]">
            {{ $articles->links() }}
        </div>
    </div>

</div>

<!-- Modal Buat Pengumuman Baru -->
<div id="modalNewArticle" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-brand-navy font-jakarta">Tulis Pengumuman CMS Baru</h3>
            <button onclick="document.getElementById('modalNewArticle').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('cms.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Judul Pengumuman</label>
                <input type="text" name="title" required placeholder="Contoh: Pembaruan Batas Limit Transaksi RTGS" 
                       class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
            </div>

            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Kategori</label>
                <select name="category" required class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
                    <option value="Kebijakan Suku Bunga">Kebijakan Suku Bunga</option>
                    <option value="Pengumuman Resmi">Pengumuman Resmi</option>
                    <option value="Keamanan Sistem">Keamanan Sistem</option>
                    <option value="Info Operasional">Info Operasional</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Divisi / Penulis</label>
                <input type="text" name="author" placeholder="Contoh: Divisi Kepatuhan & Regulasi" 
                       class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue">
            </div>

            <div>
                <label class="block text-xs font-bold text-brand-navy uppercase tracking-wider mb-1.5">Isi Pengumuman</label>
                <textarea name="content" rows="4" required placeholder="Tuliskan detail pengumuman resmi di sini..." 
                          class="w-full bg-[#F5F7FA] border border-slate-200 text-xs rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-blue/30 focus:border-brand-blue"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalNewArticle').classList.add('hidden')" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-brand-blue hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-blue/30">
                    Terbitkan Pengumuman
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

