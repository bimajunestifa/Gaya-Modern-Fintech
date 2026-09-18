@extends('layouts.app')

@section('title', 'Audit & Security Trail')
@section('header_title', 'Audit Log & Jejak Keamanan Sistem')

@section('content')
<div class="space-y-8">

    <!-- Audit Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-brand-navy font-jakarta">Jejak Audit Operasional Perbankan</h2>
            <p class="text-xs text-brand-muted mt-0.5">Catatan kepatuhan dan forensik digital seluruh aktivitas upload CSV, ekspor data, dan transaksi</p>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Audit Trail Aktif (Tamper-evident)</span>
        </div>
    </div>

    <!-- Audit Table -->
    <div class="bg-white rounded-3xl border border-slate-100/80 shadow-soft overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-brand-navy font-jakarta">Log Aktivitas Sistem</h3>
            <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                {{ $logs->total() }} Log Tercatat
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">Waktu (Timestamp)</th>
                        <th class="px-6 py-4">Aksi / Event</th>
                        <th class="px-6 py-4">Petugas / Pengguna</th>
                        <th class="px-6 py-4">Alamat IP</th>
                        <th class="px-6 py-4">Rincian Operasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-slate-500 font-mono whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold 
                                    {{ $log->action == 'UPLOAD_CSV' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($log->action == 'EXPORT_REPORT' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-brand-navy whitespace-nowrap">
                                {{ $log->user_name }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-mono whitespace-nowrap">
                                {{ $log->ip_address ?: '127.0.0.1' }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 max-w-lg">
                                {{ $log->details }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat audit log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 bg-[#F8FAFC]">
            {{ $logs->links() }}
        </div>
    </div>

</div>
@endsection

