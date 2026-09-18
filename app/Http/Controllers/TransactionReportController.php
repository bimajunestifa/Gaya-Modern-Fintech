<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\BankAccount;
use App\Models\AuditLog;
use Carbon\Carbon;

class TransactionReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('account');

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }
        if ($request->filled('account_id')) {
            $query->where('bank_account_id', $request->account_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_no', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhere('recipient_sender', 'like', "%{$s}%");
            });
        }

        // Calculate summary for the filtered query
        $totalCredit = (clone $query)->where('type', 'credit')->sum('amount');
        $totalDebit = (clone $query)->where('type', 'debit')->sum('amount');
        $netCashflow = $totalCredit - $totalDebit;
        $totalCount = (clone $query)->count();

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $accounts = BankAccount::orderBy('account_name')->get();
        $categories = Transaction::select('category')->distinct()->pluck('category');

        return view('reports.transactions', compact(
            'transactions',
            'accounts',
            'categories',
            'totalCredit',
            'totalDebit',
            'netCashflow',
            'totalCount'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = Transaction::with('account');

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }
        if ($request->filled('account_id')) {
            $query->where('bank_account_id', $request->account_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('reference_no', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhere('recipient_sender', 'like', "%{$s}%");
            });
        }

        $records = $query->orderBy('transaction_date', 'desc')->get();

        AuditLog::create([
            'action' => 'EXPORT_REPORT',
            'user_name' => 'Bank Officer / Admin',
            'ip_address' => $request->ip(),
            'details' => "Mengekspor " . count($records) . " data transaksi ke file Excel CSV.",
        ]);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan_transaksi_bank_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($records) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No Referensi',
                'Tanggal',
                'Nomor Rekening',
                'Nama Rekening',
                'Tipe',
                'Kategori',
                'Nominal (IDR)',
                'Saldo Setelah Transaksi',
                'Keterangan',
                'Pihak Terkait',
                'Status'
            ], ';');

            foreach ($records as $tx) {
                fputcsv($handle, [
                    $tx->reference_no,
                    $tx->transaction_date->format('d/m/Y'),
                    $tx->account ? $tx->account->account_number : '-',
                    $tx->account ? $tx->account->account_name : '-',
                    $tx->type == 'credit' ? 'Uang Masuk (CR)' : 'Uang Keluar (DB)',
                    $tx->category,
                    number_format($tx->amount, 2, ',', '.'),
                    number_format($tx->balance_after, 2, ',', '.'),
                    $tx->description,
                    $tx->recipient_sender,
                    $tx->status
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function statement(Request $request, $id = null)
    {
        $account = $id ? BankAccount::find($id) : BankAccount::first();

        if (!$account) {
            return redirect()->route('accounts.index')->with('error', 'Belum ada rekening nasabah terdaftar untuk mencetak rekening koran. Silakan buat rekening terlebih dahulu.');
        }
        
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $transactions = Transaction::where('bank_account_id', $account->id)
            ->whereDate('transaction_date', '>=', $dateFrom)
            ->whereDate('transaction_date', '<=', $dateTo)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $totalCredit = $transactions->where('type', 'credit')->sum('amount');
        $totalDebit = $transactions->where('type', 'debit')->sum('amount');

        return view('reports.statement', compact(
            'account',
            'transactions',
            'dateFrom',
            'dateTo',
            'totalCredit',
            'totalDebit'
        ));
    }

    public function quickTransfer(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10000',
            'recipient_name' => 'required|string|max:255',
            'recipient_role' => 'nullable|string|max:100',
            'recipient_account' => 'required|string|max:100',
            'notes' => 'nullable|string|max:255',
        ]);

        $amount = (float) $validated['amount'];
        $recipientName = $validated['recipient_name'];
        $recipientRole = $validated['recipient_role'] ?? '';
        $recipientAccount = $validated['recipient_account'];
        $notes = $validated['notes'] ?: ('Quick Transfer BI-FAST ke ' . $recipientName);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $card = \App\Models\BankCard::first();
            $account = $card ? $card->account : BankAccount::first();

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rekening sumber dana tidak ditemukan.'
                ], 404);
            }

            if ($account->balance < $amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo rekening Anda tidak mencukupi untuk melakukan transfer sejumlah ini.'
                ], 422);
            }

            // Deduct balance from BankAccount and BankCard
            $account->decrement('balance', $amount);
            $account->refresh();
            if ($card) {
                $card->decrement('balance', $amount);
                $card->refresh();
            }

            // Reference number
            $refNo = 'TRX-TRF-' . date('Ymd') . '-' . rand(1000, 9999);

            // Record transaction in database
            $trx = Transaction::create([
                'reference_no' => $refNo,
                'bank_account_id' => $account->id,
                'type' => 'debit',
                'category' => 'Transfer Keluar',
                'amount' => $amount,
                'balance_after' => $account->balance,
                'recipient_sender' => $recipientName . ($recipientRole ? ' (' . $recipientRole . ')' : '') . ' - ' . $recipientAccount,
                'description' => $notes,
                'transaction_date' => Carbon::now(),
                'status' => 'COMPLETED',
            ]);

            // Audit Trail Log
            AuditLog::create([
                'action' => 'QUICK_TRANSFER_BIFast',
                'user_name' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->name : 'Eddy Cusuma',
                'details' => "Transfer dana BI-FAST Rp " . number_format($amount, 0, ',', '.') . " berhasil ditransfer ke {$recipientName} ({$recipientAccount}). Ref: {$refNo}",
                'ip_address' => $request->ip() ?: '127.0.0.1',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            $totalLiquidity = BankAccount::sum('balance');

            return response()->json([
                'success' => true,
                'ref_no' => $trx->reference_no,
                'amount' => $amount,
                'formatted_amount' => 'Rp ' . number_format($amount, 0, ',', '.'),
                'new_card_balance' => $card ? $card->balance : $account->balance,
                'formatted_new_card_balance' => 'Rp ' . number_format($card ? $card->balance : $account->balance, 0, ',', '.'),
                'total_liquidity' => $totalLiquidity,
                'formatted_total_liquidity' => 'Rp ' . number_format($totalLiquidity, 0, ',', '.'),
                'recipient_name' => $recipientName,
                'recipient_role' => $recipientRole,
                'recipient_account' => $recipientAccount,
                'notes' => $notes,
                'date_str' => Carbon::now()->format('d M Y, H:i') . ' WIB',
                'transaction' => [
                    'description' => $trx->description,
                    'date' => Carbon::now()->format('d F Y'),
                    'category' => $trx->category,
                    'amount_formatted' => '-Rp ' . number_format($amount, 0, ',', '.'),
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses transfer: ' . $e->getMessage()
            ], 500);
        }
    }
}
