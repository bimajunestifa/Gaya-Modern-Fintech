<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\Transaction;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CsvImportController extends Controller
{
    public function index()
    {
        $accounts = BankAccount::orderBy('account_name')->get();
        $recentUploadLogs = AuditLog::where('action', 'UPLOAD_CSV')
            ->latest()
            ->take(5)
            ->get();

        return view('reports.import_csv', compact('accounts', 'recentUploadLogs'));
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_transaksi_bank_excel.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            fputcsv($handle, [
                'Tanggal (YYYY-MM-DD)',
                'No Rekening',
                'Tipe (credit/debit)',
                'Kategori',
                'Nominal',
                'Keterangan',
                'Penerima atau Pengirim',
                'Status'
            ], ';');

            $today = Carbon::now()->format('Y-m-d');
            $yesterday = Carbon::now()->subDay()->format('Y-m-d');

            fputcsv($handle, [
                $today,
                '1029-3847-5612',
                'credit',
                'Deposit',
                '25000000',
                'Setoran Tunai Teller Cabang Sudirman',
                'Eddy Cusuma',
                'Completed'
            ], ';');

            fputcsv($handle, [
                $today,
                '1029-3847-5612',
                'debit',
                'Bill Payment',
                '3500000',
                'Pembayaran Pajak & Tagihan Kantor',
                'Kas Negara DJP',
                'Completed'
            ], ';');

            fputcsv($handle, [
                $yesterday,
                '2093-8475-1029',
                'credit',
                'Transfer',
                '18250000',
                'Pembayaran Invoice Vendor Proyek IT',
                'PT Solusi Media Solusindo',
                'Completed'
            ], ';');

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadTemplateExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Transaksi Bank');

        $headers = [
            'A1' => 'Tanggal (YYYY-MM-DD)',
            'B1' => 'No Rekening',
            'C1' => 'Tipe (credit/debit)',
            'D1' => 'Kategori',
            'E1' => 'Nominal',
            'F1' => 'Keterangan',
            'G1' => 'Penerima atau Pengirim',
            'H1' => 'Status'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $headerRange = 'A1:H1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2D60FF'); // Royal Blue
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        $rows = [
            [$today, '1029-3847-5612', 'credit', 'Deposit', 25000000, 'Setoran Tunai Teller Cabang Sudirman', 'Eddy Cusuma', 'Completed'],
            [$today, '1029-3847-5612', 'debit', 'Bill Payment', 3500000, 'Pembayaran Pajak & Tagihan Kantor', 'Kas Negara DJP', 'Completed'],
            [$yesterday, '2093-8475-1029', 'credit', 'Transfer', 18250000, 'Pembayaran Invoice Vendor Proyek IT', 'PT Solusi Media Solusindo', 'Completed'],
        ];

        $rowIndex = 2;
        foreach ($rows as $row) {
            $sheet->fromArray($row, null, 'A' . $rowIndex);
            $rowIndex++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'template_transaksi_bank.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:20480',
            'target_account_id' => 'nullable|exists:bank_accounts,id',
        ]);

        $file = $request->file('csv_file');
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        $rows = [];

        if (in_array($ext, ['xlsx', 'xls'])) {
            try {
                $reader = IOFactory::createReaderForFile($path);
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($path);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray(null, true, false, false);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
            }
        } else {
            $sample = file_get_contents($path, false, null, 0, 2048);
            $delimiter = (substr_count($sample, ';') > substr_count($sample, ',')) ? ';' : ',';

            $handle = fopen($path, 'r');
            if (!$handle) {
                return back()->with('error', 'Gagal membuka file CSV yang diunggah.');
            }

            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return back()->with('error', 'File yang diunggah kosong atau format tidak sesuai.');
        }

        // Header detection
        $headerRowIndex = 0;
        $colMap = [
            'date' => 0,
            'account' => 1,
            'type' => 2,
            'category' => 3,
            'amount' => 4,
            'desc' => 5,
            'recipient' => 6,
            'status' => 7,
        ];

        // Scan header row
        $firstRow = $rows[0] ?? [];
        if (is_array($firstRow)) {
            foreach ($firstRow as $cIdx => $cellVal) {
                $clean = preg_replace('/[^a-z0-9]/', '', strtolower(trim((string)$cellVal)));
                if (preg_match('/(tanggal|date|tgl|transdate)/', $clean)) {
                    $colMap['date'] = $cIdx;
                } elseif (preg_match('/(norek|nomorrek|rekening|accountno|acc)/', $clean)) {
                    $colMap['account'] = $cIdx;
                } elseif (preg_match('/(tipe|type|dc|debitcredit|mutasi)/', $clean)) {
                    $colMap['type'] = $cIdx;
                } elseif (preg_match('/(kategori|category)/', $clean)) {
                    $colMap['category'] = $cIdx;
                } elseif (preg_match('/(nominal|amount|jumlah|nilai)/', $clean)) {
                    $colMap['amount'] = $cIdx;
                } elseif (preg_match('/(keterangan|deskripsi|description|uraian)/', $clean)) {
                    $colMap['desc'] = $cIdx;
                } elseif (preg_match('/(penerima|pengirim|recipient|counterparty)/', $clean)) {
                    $colMap['recipient'] = $cIdx;
                } elseif (preg_match('/(status)/', $clean)) {
                    $colMap['status'] = $cIdx;
                }
            }
        }

        try {
            DB::beginTransaction();

            $importedCount = 0;
            $totalCredit = 0;
            $totalDebit = 0;
            $fallbackAccount = $request->target_account_id ? BankAccount::find($request->target_account_id) : BankAccount::first();

            for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (!is_array($row) || empty(array_filter($row))) {
                    continue;
                }

                $rawDate = trim((string)($row[$colMap['date']] ?? ''));
                $rawAccountNum = trim((string)($row[$colMap['account']] ?? ''));
                $rawType = strtolower(trim((string)($row[$colMap['type']] ?? 'credit')));
                $category = trim((string)($row[$colMap['category']] ?? 'General'));
                $rawAmount = $row[$colMap['amount']] ?? '0';
                $desc = trim((string)($row[$colMap['desc']] ?? 'Import Transaksi Excel'));
                $recipient = trim((string)($row[$colMap['recipient']] ?? 'Bank Client'));
                $status = ucfirst(strtolower(trim((string)($row[$colMap['status']] ?? 'Completed'))));

                // Parse Date
                try {
                    $date = Carbon::parse($rawDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $date = Carbon::now()->format('Y-m-d');
                }

                // Standardize Type
                $type = in_array($rawType, ['debit', 'keluar', 'out', 'dr', 'd', 'pengeluaran', 'tarik']) ? 'debit' : 'credit';

                // Amount
                $amount = $this->parseNumericValue($rawAmount);
                if ($amount <= 0) {
                    continue;
                }

                // Identify or match Account
                $account = null;
                if (!empty($rawAccountNum)) {
                    $account = BankAccount::where('account_number', $rawAccountNum)->first();
                }
                if (!$account) {
                    $account = $fallbackAccount;
                }

                // If still no account, create one
                if (!$account) {
                    $account = BankAccount::create([
                        'account_number' => $rawAccountNum ?: 'ACC-' . rand(1000, 9999),
                        'account_name' => $recipient ?: 'Nasabah Baru',
                        'account_type' => 'Tabungan Reguler',
                        'balance' => 0,
                        'status' => 'Active',
                    ]);
                }

                // Update balance
                if ($type === 'credit') {
                    $account->balance += $amount;
                    $totalCredit += $amount;
                } else {
                    $account->balance -= $amount;
                    $totalDebit += $amount;
                }
                $account->save();

                // Insert Transaction
                $refNo = 'TRX-IMP-' . strtoupper(Str::random(6)) . '-' . date('Ymd');
                Transaction::create([
                    'reference_no' => $refNo,
                    'bank_account_id' => $account->id,
                    'type' => $type,
                    'category' => $category ?: 'General',
                    'amount' => $amount,
                    'balance_after' => $account->balance,
                    'recipient_sender' => $recipient,
                    'description' => $desc,
                    'transaction_date' => $date,
                    'status' => in_array($status, ['Completed', 'Pending', 'Failed']) ? $status : 'Completed',
                ]);

                $importedCount++;
            }

            // Record to Audit Log
            AuditLog::create([
                'action' => 'UPLOAD_CSV',
                'user_name' => 'Bank Officer / Admin',
                'ip_address' => $request->ip(),
                'details' => "Berhasil mengimpor {$importedCount} transaksi bank dari file Excel. Total Kredit: Rp " . number_format($totalCredit, 2, ',', '.') . ", Total Debit: Rp " . number_format($totalDebit, 2, ',', '.'),
            ]);

            DB::commit();

            return redirect()->route('transactions.index')->with('success', "Sukses! Berhasil mengimpor {$importedCount} transaksi bank dari file Excel. Total Kredit: Rp " . number_format($totalCredit, 0, ',', '.') . ", Total Debit: Rp " . number_format($totalDebit, 0, ',', '.'));

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Transaction Import Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Gagal memproses file transaksi: ' . $e->getMessage() . '. Sistem berhasil mengamankan database.');
        }
    }

    private function parseNumericValue($val): float
    {
        if (is_numeric($val)) {
            $num = floatval($val);
            if ($num > 10000000000000) {
                return 0.0;
            }
            return max(0.0, min($num, 9999999999999.99));
        }

        $str = trim((string)$val);
        if (empty($str)) {
            return 0.0;
        }

        // Tangani notasi ilmiah Excel (misal: 8.0301E+16)
        if (preg_match('/^[+-]?[0-9]+(\.[0-9]+)?[eE][+-]?[0-9]+$/', $str)) {
            $num = floatval($str);
            if ($num > 10000000000000) {
                return 0.0;
            }
            return max(0.0, min($num, 9999999999999.99));
        }

        $str = preg_replace('/[^\d\.,\-]/', '', $str);
        if (empty($str)) {
            return 0.0;
        }

        if (strpos($str, '.') !== false && strpos($str, ',') !== false) {
            if (strrpos($str, ',') > strrpos($str, '.')) {
                $str = str_replace('.', '', $str);
                $str = str_replace(',', '.', $str);
            } else {
                $str = str_replace(',', '', $str);
            }
        } elseif (strpos($str, ',') !== false) {
            $parts = explode(',', $str);
            if (count($parts) == 2 && strlen($parts[1]) <= 2) {
                $str = str_replace(',', '.', $str);
            } else {
                $str = str_replace(',', '', $str);
            }
        } elseif (strpos($str, '.') !== false) {
            $parts = explode('.', $str);
            if (count($parts) > 2 || (count($parts) == 2 && strlen($parts[1]) == 3)) {
                $str = str_replace('.', '', $str);
            }
        }

        $num = floatval($str);
        if ($num > 10000000000000) {
            return 0.0;
        }
        return max(0.0, min($num, 9999999999999.99));
    }
}
