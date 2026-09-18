<?php

namespace App\Http\Controllers\Psak413;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Psak413JournalEntry;
use App\Models\Psak413CreditPortfolio;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Psak413JournalController extends Controller
{
    public function index()
    {
        $journals = Psak413JournalEntry::orderBy('entry_date', 'desc')->orderBy('id', 'asc')->paginate(15);
        $totalDebit = Psak413JournalEntry::sum('debit');
        $totalCredit = Psak413JournalEntry::sum('credit');
        $isBalanced = abs($totalDebit - $totalCredit) < 0.01;

        return view('psak413.journals', compact('journals', 'totalDebit', 'totalCredit', 'isBalanced'));
    }

    public function generate(Request $request)
    {
        $today = Carbon::now();
        $totalEcl = Psak413CreditPortfolio::sum('ecl_allowance');
        $totalKafalah = Psak413CreditPortfolio::sum('kafalah_provision_amount');
        $batchNo = 'JV-PSAK413-' . date('Ymd-His');

        DB::beginTransaction();
        try {
            // Jurnal 1: Beban ECL Syariah
            Psak413JournalEntry::create([
                'entry_number' => $batchNo,
                'entry_date' => $today,
                'account_code' => '5.1.01.01',
                'account_name' => 'Beban Penurunan Nilai Pembiayaan Syariah (ECL PSAK 413)',
                'contract_type' => 'MULTI_AKAD',
                'debit' => $totalEcl,
                'credit' => 0.00,
                'description' => 'Penyesuaian cadangan kerugian penurunan nilai (CKPN) pembiayaan syariah periode ' . $today->format('d M Y'),
            ]);

            Psak413JournalEntry::create([
                'entry_number' => $batchNo,
                'entry_date' => $today,
                'account_code' => '1.4.09.01',
                'account_name' => 'Cadangan Kerugian Penurunan Nilai (CKPN) Pembiayaan Syariah',
                'contract_type' => 'MULTI_AKAD',
                'debit' => 0.00,
                'credit' => $totalEcl,
                'description' => 'Kontra Aset Neraca - Akumulasi CKPN Pembiayaan Syariah PSAK 413',
            ]);

            // Jurnal 2: Provisi Kafalah
            if ($totalKafalah > 0) {
                Psak413JournalEntry::create([
                    'entry_number' => $batchNo,
                    'entry_date' => $today,
                    'account_code' => '5.1.02.01',
                    'account_name' => 'Beban Pembentukan Provisi Kafalah Penjaminan Kredit',
                    'contract_type' => 'KAFALAH',
                    'debit' => $totalKafalah,
                    'credit' => 0.00,
                    'description' => 'Pembentukan provisi kewajiban kontinjensi penjaminan risiko kredit syariah (Kafalah)',
                ]);

                Psak413JournalEntry::create([
                    'entry_number' => $batchNo,
                    'entry_date' => $today,
                    'account_code' => '2.3.05.01',
                    'account_name' => 'Liabilitas Kontinjensi - Provisi Kafalah Penjaminan',
                    'contract_type' => 'KAFALAH',
                    'debit' => 0.00,
                    'credit' => $totalKafalah,
                    'description' => 'Provisi penjaminan risiko kredit syariah (Kafalah) pada sisi Liabilitas Neraca',
                ]);
            }

            AuditLog::create([
                'action' => 'PSAK413_GENERATE_JOURNAL',
                'user_name' => Auth::check() ? Auth::user()->name : 'Bank Officer',
                'ip_address' => $request->ip(),
                'details' => "Generate batch jurnal akuntansi syariah PSAK 413 {$batchNo} total Rp " . number_format($totalEcl + $totalKafalah, 0, ',', '.'),
            ]);

            DB::commit();
            return back()->with('success', "Batch Jurnal PSAK 413 ({$batchNo}) berhasil dibukukan dengan status Berimbang (Balanced)!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membukukan jurnal: ' . $e->getMessage());
        }
    }
}

