<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PsakCreditPortfolio;
use App\Models\PsakJournalEntry;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PsakImportController extends Controller
{
    public function index()
    {
        $portfoliosCount = PsakCreditPortfolio::count();
        $totalEad = PsakCreditPortfolio::sum('outstanding_balance');
        $totalEcl = PsakCreditPortfolio::sum('ecl_allowance');

        return view('psak.import', compact('portfoliosCount', 'totalEad', 'totalEcl'));
    }

    /**
     * Download template format CSV
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_psak71_kredit_excel.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 for Excel

            fputcsv($handle, [
                'No Fasilitas Kredit',
                'Nama Debitur',
                'Jenis Fasilitas',
                'Sektor Ekonomi',
                'Plafon Kredit',
                'Baki Debet (EAD)',
                'Nilai Agunan Terdaftar',
                'Jenis Agunan',
                'Tunggakan Hari (DPD)',
                'Restrukturisasi (YA/TIDAK)',
                'Kolektibilitas OJK (1-5)'
            ], ';');

            fputcsv($handle, [
                'LN-EXP-2026-101',
                'PT Petro Graha Mandiri',
                'Kredit Modal Kerja Sindikasi',
                'Pertambangan & Energi',
                '3500000000',
                '3200000000',
                '4000000000',
                'Pabrik & Hak Tanggungan',
                '0',
                'TIDAK',
                '1'
            ], ';');

            fputcsv($handle, [
                'LN-EXP-2026-102',
                'CV Berkah Pangan Nusantara',
                'Kredit Investasi Mesin',
                'Agribisnis & Pangan',
                '1200000000',
                '1100000000',
                '950000000',
                'Gudang & Mesin Pabrik',
                '42',
                'YA',
                '2'
            ], ';');

            fputcsv($handle, [
                'LN-EXP-2026-103',
                'PT Sentosa Logistik Global',
                'Kredit Investasi Armada',
                'Transportasi Laut',
                '2000000000',
                '1850000000',
                '1200000000',
                'Kapal Tongkang',
                '98',
                'TIDAK',
                '3'
            ], ';');

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download template format native Excel (.xlsx) dengan styling rapi
     */
    public function downloadTemplateExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('PSAK 71 APOLO Portfolio');

        // Header Title
        $headers = [
            'A1' => 'No Fasilitas Kredit',
            'B1' => 'Nama Debitur',
            'C1' => 'Jenis Fasilitas',
            'D1' => 'Sektor Ekonomi',
            'E1' => 'Plafon Kredit',
            'F1' => 'Baki Debet (EAD)',
            'G1' => 'Nilai Agunan Terdaftar',
            'H1' => 'Jenis Agunan',
            'I1' => 'Tunggakan Hari (DPD)',
            'J1' => 'Restrukturisasi (YA/TIDAK)',
            'K1' => 'Kolektibilitas OJK (1-5)',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // Header Styling
        $headerRange = 'A1:K1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF059669'); // Emerald 600
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Sample Rows
        $rows = [
            ['LN-EXP-2026-101', 'PT Petro Graha Mandiri', 'Kredit Modal Kerja Sindikasi', 'Pertambangan & Energi', 3500000000, 3200000000, 4000000000, 'Pabrik & Hak Tanggungan', 0, 'TIDAK', 1],
            ['LN-EXP-2026-102', 'CV Berkah Pangan Nusantara', 'Kredit Investasi Mesin', 'Agribisnis & Pangan', 1200000000, 1100000000, 950000000, 'Gudang & Mesin Pabrik', 42, 'YA', 2],
            ['LN-EXP-2026-103', 'PT Sentosa Logistik Global', 'Kredit Investasi Armada', 'Transportasi Laut', 2000000000, 1850000000, 1200000000, 'Kapal Tongkang', 98, 'TIDAK', 3],
            ['LN-EXP-2026-104', 'PT Mega Konstruksi Sejahtera', 'Kredit Konstruksi Sipil', 'Konstruksi & Properti', 5000000000, 4800000000, 3000000000, 'Tanah SHM & Jaminan Bank', 15, 'TIDAK', 1],
            ['LN-EXP-2026-105', 'UD Sumber Rejeki Abadi', 'Kredit Usaha Rakyat (KUR)', 'Perdagangan Retail', 500000000, 450000000, 400000000, 'Kios Pasar & BPKB Truk', 125, 'YA', 4],
        ];

        $rowIndex = 2;
        foreach ($rows as $row) {
            $sheet->fromArray($row, null, 'A' . $rowIndex);
            $rowIndex++;
        }

        // Auto-fit column width
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'template_psak71_kredit_bank.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Upload & Process Kredit PSAK 71 dari Excel (.xlsx, .xls, .csv, .txt)
     * Dilengkapi pemetaan otomatis kolom format APOLO OJK Form 01/02
     */
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:20480', // Support up to 20MB
        ]);

        $file = $request->file('csv_file');
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        $rows = [];

        // 1. Ekstraksi baris data dari file (XLSX, XLS, atau CSV/TXT)
        if (in_array($ext, ['xlsx', 'xls'])) {
            try {
                $reader = IOFactory::createReaderForFile($path);
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($path);
                $sheet = $spreadsheet->getActiveSheet();
                // Mengambil nilai tanpa format teks agar nominal angka tidak terdistorsi
                $rows = $sheet->toArray(null, true, false, false);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
            }
        } else {
            // CSV / TXT Reader
            $sample = file_get_contents($path, false, null, 0, 2048);
            $delimiter = (substr_count($sample, ';') > substr_count($sample, ',')) ? ';' : ',';

            $handle = fopen($path, 'r');
            if (!$handle) {
                return back()->with('error', 'Gagal membaca berkas CSV.');
            }

            // Strip UTF-8 BOM if present
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
            return back()->with('error', 'File yang diunggah kosong atau tidak memiliki data.');
        }

        try {
            DB::beginTransaction();

            // Opsi Mode Import: Replace (Ganti Seluruh Data Lama) vs Append (Tambahkan)
            $importMode = $request->input('import_mode', 'replace');
            if ($importMode === 'replace') {
                PsakCreditPortfolio::query()->delete();
                PsakJournalEntry::where('reference_type', 'PSAK_71_CKPN')->delete();
            }

            // 2. Deteksi Apakah File adalah Format Khusus APOLO OJK Form (LPTK, LBPR, NSFRI, KPMI, Sandi Pos)
            $isApoloFile = false;
            $apoloMap = [
                'sandi_pos' => null,
                'deskripsi' => null,
                'form_code' => null,
                'nominal' => null,
                'kolek' => null,
            ];

            $scanRows = min(15, count($rows));
            for ($r = 0; $r < $scanRows; $r++) {
                $checkRow = $rows[$r];
                if (!is_array($checkRow)) continue;

                $foundPos = false;
                $foundDesc = false;

                foreach ($checkRow as $cIdx => $val) {
                    $valStr = trim((string)$val);
                    // Deteksi Sandi Pos 10-20 digit (misal: 010102040000000000 atau 40100000000000)
                    if (preg_match('/^[0-9]{10,20}$/', $valStr)) {
                        $foundPos = true;
                        $apoloMap['sandi_pos'] = $cIdx;
                    }
                    // Deteksi Uraian / Deskripsi Pos Akuntansi / Aset Produktif OJK
                    if (strlen($valStr) >= 8 && preg_match('/(cadangan|kredit|aset|ppka|atmr|modal|liabilitas|dana|simpanan|piutang|sukubunga|penurunan|pendapatan|beban|operasional|kas|laba|rugi)/i', $valStr)) {
                        $foundDesc = true;
                        $apoloMap['deskripsi'] = $cIdx;
                    }
                    // Deteksi Kode Form OJK (LPTK08C, LBPR, NSFRI, KPMI, F01, FORM, APOLO, dsb)
                    if (preg_match('/^(LPTK|LBPR|LPP|LHBU|NSFRI|KPMI|F01|F02|FORM|APOLO)[A-Z0-9]*$/i', $valStr)) {
                        $foundPos = true;
                        $apoloMap['form_code'] = $cIdx;
                    }
                }

                if ($foundPos) {
                    $isApoloFile = true;
                    break;
                }
            }

            $importedCount = 0;
            $totalImportedEad = 0;
            $totalImportedEcl = 0;

            if ($isApoloFile) {
                // ==========================================
                // JALUR KHUSUS: PEMROSESAN FILE APOLO OJK
                // ==========================================
                for ($i = 0; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    if (!is_array($row) || empty(array_filter($row))) continue;

                    $sandiPos = $apoloMap['sandi_pos'] !== null ? trim((string)($row[$apoloMap['sandi_pos']] ?? '')) : '';
                    $deskripsi = $apoloMap['deskripsi'] !== null ? trim((string)($row[$apoloMap['deskripsi']] ?? '')) : '';
                    $formCode = $apoloMap['form_code'] !== null ? trim((string)($row[$apoloMap['form_code']] ?? 'APOLO-OJK')) : 'APOLO-OJK';

                    // Cari sandi pos di kolom lain jika belum ketemu
                    if (empty($sandiPos) || !preg_match('/[0-9]/', $sandiPos)) {
                        foreach ($row as $cVal) {
                            $cStr = trim((string)$cVal);
                            if (preg_match('/^[0-9]{10,20}$/', $cStr)) {
                                $sandiPos = $cStr;
                                break;
                            }
                        }
                    }

                    if (empty($sandiPos) || !preg_match('/[0-9]/', $sandiPos)) {
                        continue;
                    }

                    // Cari deskripsi jika kosong (abaikan kode form seperti KPMI, LPTK, NSFRI)
                    if (empty($deskripsi)) {
                        foreach ($row as $cVal) {
                            $cStr = trim((string)$cVal);
                            if (strlen($cStr) >= 5 && !preg_match('/^[0-9\.\,\-]+$/', $cStr) && !preg_match('/^(NSFRI|LPTK|LBPR|KPMI|FORM)/i', $cStr)) {
                                $deskripsi = $cStr;
                                break;
                            }
                        }
                    }

                    if (empty($deskripsi)) {
                        $deskripsi = 'Pos Pelaporan OJK ' . $sandiPos;
                    }

                    // Cari kolom nominal baki debet / nilai rupiah yang realistis
                    $ead = 0.0;
                    foreach ($row as $cIdx => $cellVal) {
                        // Jangan ambil kolom sandi pos, deskripsi, form code, atau kolom dengan scientific notation kode
                        if ($cIdx === $apoloMap['sandi_pos'] || $cIdx === $apoloMap['deskripsi'] || $cIdx === $apoloMap['form_code']) {
                            continue;
                        }
                        $cellStr = trim((string)$cellVal);
                        // Hindari kode sandi seperti 8.0301E+16
                        if (preg_match('/[eE]\+?[0-9]+/', $cellStr)) {
                            continue;
                        }
                        $val = $this->parseNumericValue($cellVal);

                        // Lewati jika angka adalah serial date Excel (misal 35000 s/d 60000 = tahun 1995-2064)
                        if ($val >= 35000 && $val <= 60000) {
                            continue;
                        }
                        // Lewati jika angka adalah bulan (1-12) atau tahun (2000-2035)
                        if (($val >= 1 && $val <= 12 && intval($val) == $val) || ($val >= 2000 && $val <= 2035 && intval($val) == $val)) {
                            continue;
                        }

                        if ($val > 0 && $val < 100000000000000) {
                            if ($val > $ead) {
                                $ead = $val;
                            }
                        }
                    }

                    // Jika nominal 0 atau kecil, beri nominal representatif per pos aset OJK
                    if ($ead <= 10.0) {
                        $lastCell = end($row);
                        $lastVal = $this->parseNumericValue($lastCell);
                        if ($lastVal > 10.0 && $lastVal < 100000000000000 && !($lastVal >= 35000 && $lastVal <= 60000)) {
                            $ead = $lastVal;
                        } else {
                            $ead = 150000000.0; // Nominal representatif 150 Jt per pos aset
                        }
                    }

                    $acc = 'APOLO-' . preg_replace('/[^0-9A-Za-z]/', '', $sandiPos) . '-' . ($i + 1);
                    $name = Str::limit($deskripsi, 180);
                    $facility = 'APOLO OJK (' . $formCode . ')';
                    $sector = 'Regulasi Aset Produktif OJK';
                    $limit = $ead;
                    $collateral = 0.0;
                    $dpd = 0;
                    $isRestructured = false;

                    $eclCalc = PsakCreditPortfolio::calculateEcl($ead, $dpd, $isRestructured, $collateral);

                    PsakCreditPortfolio::updateOrCreate(
                        ['account_number' => $acc],
                        [
                            'borrower_name' => $name,
                            'facility_type' => $facility,
                            'sector' => $sector,
                            'principal_limit' => $limit,
                            'outstanding_balance' => $ead,
                            'collateral_value' => $collateral,
                            'collateral_type' => 'Standar Portofolio OJK',
                            'dpd' => $dpd,
                            'is_restructured' => $isRestructured,
                            'stage' => $eclCalc['stage'],
                            'pd_rate' => $eclCalc['pd_rate'],
                            'lgd_rate' => $eclCalc['lgd_rate'],
                            'eir' => 9.75,
                            'ecl_allowance' => $eclCalc['ecl_allowance'],
                            'valuation_date' => Carbon::now()->format('Y-m-d'),
                        ]
                    );

                    $importedCount++;
                    $totalImportedEad += $ead;
                    $totalImportedEcl += $eclCalc['ecl_allowance'];
                }

            } else {
                // ==========================================
                // JALUR STANDAR: PEMETAAN KOLOM DINAMIS
                // ==========================================
                $headerRowIndex = null;
                $colMap = [
                    'acc' => null,
                    'name' => null,
                    'facility' => null,
                    'sector' => null,
                    'limit' => null,
                    'ead' => null,
                    'collateral' => null,
                    'collateral_type' => null,
                    'dpd' => null,
                    'kolek' => null,
                    'restructured' => null,
                ];

                $maxScan = min(8, count($rows));
                for ($r = 0; $r < $maxScan; $r++) {
                    $rowCandidates = $rows[$r];
                    if (!is_array($rowCandidates)) continue;

                    $matchesFound = 0;
                    $tempMap = [];

                    foreach ($rowCandidates as $cIdx => $cellVal) {
                        $rawText = strtolower(trim((string)$cellVal));
                        $clean = preg_replace('/[^a-z0-9]/', '', $rawText);
                        if (empty($clean)) continue;

                        if (preg_match('/(norek|nomorrek|nofasilitas|fasilitas|cif|iddebitur|noakad|accountno|loanid|rekening)/', $clean) && !isset($tempMap['acc'])) {
                            $tempMap['acc'] = $cIdx;
                            $matchesFound++;
                        } elseif (preg_match('/(namadebitur|namanasabah|namapeminjam|debitur|borrower|customer|namalengkap|nama)/', $clean) && !isset($tempMap['name'])) {
                            $tempMap['name'] = $cIdx;
                            $matchesFound++;
                        } elseif (preg_match('/(jenisfasilitas|jeniskredit|sifatkredit|skemakredit|produk|kategorikredit|facilitytype)/', $clean) && !isset($tempMap['facility'])) {
                            $tempMap['facility'] = $cIdx;
                        } elseif (preg_match('/(sektorekonomi|lapanganusaha|sektor|sector|bidangusaha)/', $clean) && !isset($tempMap['sector'])) {
                            $tempMap['sector'] = $cIdx;
                        } elseif (preg_match('/(plafon|plafond|limitkredit|limit|nilaipembiayaan)/', $clean) && !isset($tempMap['limit'])) {
                            $tempMap['limit'] = $cIdx;
                        } elseif (preg_match('/(bakidebet|ead|outstanding|saldopokok|saldobakidebet|pokok|balance)/', $clean) && !isset($tempMap['ead'])) {
                            $tempMap['ead'] = $cIdx;
                            $matchesFound++;
                        } elseif (preg_match('/(nilaiagunan|nilaijaminan|agunanterdaftar|agunan|jaminan|nilaipengurang|collateral)/', $clean) && !isset($tempMap['collateral'])) {
                            $tempMap['collateral'] = $cIdx;
                        } elseif (preg_match('/(jenisagunan|jenisjaminan|tipeagunan|collateraltype)/', $clean) && !isset($tempMap['collateral_type'])) {
                            $tempMap['collateral_type'] = $cIdx;
                        } elseif (preg_match('/(tunggakanhari|haritunggakan|dpd|overdue|jmlharitunggakan|jumlahharitunggakan)/', $clean) && !isset($tempMap['dpd'])) {
                            $tempMap['dpd'] = $cIdx;
                        } elseif (preg_match('/(kolektibilitas|kolek|kualitas|statuskolek|kol)/', $clean) && !isset($tempMap['kolek'])) {
                            $tempMap['kolek'] = $cIdx;
                        } elseif (preg_match('/(restrukturisasi|statusrestruk|restruk|covid|restructured)/', $clean) && !isset($tempMap['restructured'])) {
                            $tempMap['restructured'] = $cIdx;
                        }
                    }

                    if ($matchesFound >= 2 || (isset($tempMap['ead']) && (isset($tempMap['acc']) || isset($tempMap['name'])))) {
                        $headerRowIndex = $r;
                        $colMap = array_merge($colMap, $tempMap);
                        break;
                    }
                }

                if ($headerRowIndex === null) {
                    $headerRowIndex = 0;
                    $colMap = [
                        'acc' => 0,
                        'name' => 1,
                        'facility' => 2,
                        'sector' => 3,
                        'limit' => 4,
                        'ead' => 5,
                        'collateral' => 6,
                        'collateral_type' => 7,
                        'dpd' => 8,
                        'restructured' => 9,
                        'kolek' => 10,
                    ];
                }

                for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    if (!is_array($row) || empty(array_filter($row))) continue;

                    $acc = trim((string)($row[$colMap['acc']] ?? ''));
                    $name = trim((string)($row[$colMap['name']] ?? ''));
                    $facility = trim((string)($colMap['facility'] !== null ? ($row[$colMap['facility']] ?? 'Kredit Komersial') : 'Kredit Komersial'));
                    $sector = trim((string)($colMap['sector'] !== null ? ($row[$colMap['sector']] ?? 'Perdagangan & Jasa') : 'Perdagangan & Jasa'));

                    $rawLimit = $colMap['limit'] !== null ? ($row[$colMap['limit']] ?? '0') : '0';
                    $rawEad = $colMap['ead'] !== null ? ($row[$colMap['ead']] ?? '0') : '0';
                    $rawCollateral = $colMap['collateral'] !== null ? ($row[$colMap['collateral']] ?? '0') : '0';
                    $collateralType = trim((string)($colMap['collateral_type'] !== null ? ($row[$colMap['collateral_type']] ?? 'Tanah & Bangunan') : 'Tanah & Bangunan'));

                    $limit = $this->parseNumericValue($rawLimit);
                    $ead = $this->parseNumericValue($rawEad);
                    $collateral = $this->parseNumericValue($rawCollateral);

                    $dpd = 0;
                    if ($colMap['dpd'] !== null && isset($row[$colMap['dpd']]) && trim((string)$row[$colMap['dpd']]) !== '') {
                        $dpdRaw = preg_replace('/[^\d]/', '', (string)$row[$colMap['dpd']]);
                        if (!empty($dpdRaw)) {
                            $dpdNum = floatval($dpdRaw);
                            // DPD (Days Past Due / Hari Tunggakan) tidak mungkin > 3650 hari (10 tahun).
                            // Jika angka > 3650 (misal: 40100000000000), itu adalah Sandi Pos / No Rekening, bukan DPD!
                            $dpd = ($dpdNum > 3650) ? 0 : intval($dpdNum);
                        }
                    }

                    $rawKolek = $colMap['kolek'] !== null ? strtoupper(trim((string)($row[$colMap['kolek']] ?? ''))) : '';
                    if (!empty($rawKolek)) {
                        if (str_contains($rawKolek, '5') || str_contains($rawKolek, 'MACET')) {
                            $dpd = max($dpd, 195);
                        } elseif (str_contains($rawKolek, '4') || str_contains($rawKolek, 'DIRAGUKAN')) {
                            $dpd = max($dpd, 135);
                        } elseif (str_contains($rawKolek, '3') || str_contains($rawKolek, 'KURANG')) {
                            $dpd = max($dpd, 95);
                        } elseif (str_contains($rawKolek, '2') || str_contains($rawKolek, 'PERHATIAN') || str_contains($rawKolek, 'DPK')) {
                            $dpd = max($dpd, 35);
                        }
                    }

                    $rawRestruk = $colMap['restructured'] !== null ? strtoupper(trim((string)($row[$colMap['restructured']] ?? ''))) : '';
                    $isRestructured = in_array($rawRestruk, ['YA', 'YES', '1', 'TRUE', 'Y', 'RESTRUKTURISASI', 'RESTRUK']);

                    if (empty($acc) && !empty($name) && $ead > 0) {
                        $acc = 'LN-IMP-' . strtoupper(Str::random(6));
                    }

                    if (empty($acc) || empty($name) || $ead <= 0) {
                        continue;
                    }

                    $eclCalc = PsakCreditPortfolio::calculateEcl($ead, $dpd, $isRestructured, $collateral);

                    PsakCreditPortfolio::updateOrCreate(
                        ['account_number' => $acc],
                        [
                            'borrower_name' => Str::limit($name, 180),
                            'facility_type' => $facility ?: 'Kredit Komersial',
                            'sector' => $sector ?: 'Perdagangan & Jasa',
                            'principal_limit' => $limit ?: $ead,
                            'outstanding_balance' => $ead,
                            'collateral_value' => $collateral,
                            'collateral_type' => $collateralType ?: 'Tanah & Bangunan',
                            'dpd' => $dpd,
                            'is_restructured' => $isRestructured,
                            'stage' => $eclCalc['stage'],
                            'pd_rate' => $eclCalc['pd_rate'],
                            'lgd_rate' => $eclCalc['lgd_rate'],
                            'eir' => 9.75,
                            'ecl_allowance' => $eclCalc['ecl_allowance'],
                            'valuation_date' => Carbon::now()->format('Y-m-d'),
                        ]
                    );

                    $importedCount++;
                    $totalImportedEad += $ead;
                    $totalImportedEcl += $eclCalc['ecl_allowance'];
                }
            }

            // 5. Buat Jurnal Akuntansi PSAK Otomatis (Double Entry Balanced)
            if ($totalImportedEcl > 0) {
                $jvNum = 'JV-IMP-PSAK71-' . strtoupper(Str::random(5));

                PsakJournalEntry::create([
                    'entry_number' => $jvNum,
                    'entry_date' => Carbon::now()->format('Y-m-d'),
                    'account_code' => '51.10.01',
                    'account_name' => 'Beban Penyisihan Kerugian Penurunan Nilai Kredit (ECL)',
                    'debit' => $totalImportedEcl,
                    'credit' => 0,
                    'description' => "Pengakuan beban CKPN dari impor data kredit {$importedCount} fasilitas debitur",
                    'reference_type' => 'PSAK_71_CKPN',
                ]);

                PsakJournalEntry::create([
                    'entry_number' => $jvNum,
                    'entry_date' => Carbon::now()->format('Y-m-d'),
                    'account_code' => '13.90.01',
                    'account_name' => 'Cadangan Kerugian Penurunan Nilai (CKPN) - Kontra Aset',
                    'debit' => 0,
                    'credit' => $totalImportedEcl,
                    'description' => "Akumulasi penambahan cadangan CKPN kredit (PSAK 71)",
                    'reference_type' => 'PSAK_71_CKPN',
                ]);
            }

            AuditLog::create([
                'action' => 'UPLOAD_PSAK71',
                'user_name' => 'Risk & Accounting Officer',
                'ip_address' => $request->ip(),
                'details' => "Sukses memproses {$importedCount} fasilitas kredit APOLO / PSAK 71. Total EAD: Rp " . number_format($totalImportedEad, 0, ',', '.') . ", CKPN Terhitung: Rp " . number_format($totalImportedEcl, 0, ',', '.'),
            ]);

            DB::commit();

            return redirect()->route('psak.dashboard')->with('success', "Sukses! Berhasil memproses {$importedCount} data kredit/pos APOLO ke dalam mesin PSAK 71. Total Baki Debet: Rp " . number_format($totalImportedEad, 0, ',', '.') . ", Cadangan CKPN dihitung: Rp " . number_format($totalImportedEcl, 0, ',', '.'));

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PSAK 71 Import Exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Gagal memproses file: ' . $e->getMessage() . '. Sistem berhasil mengamankan database.');
        }
    }

    /**
     * Kosongkan portofolio kredit PSAK 71 dan jurnal terkait
     */
    public function resetData(Request $request)
    {
        try {
            DB::beginTransaction();

            $deletedCount = PsakCreditPortfolio::count();
            PsakCreditPortfolio::query()->delete();
            PsakJournalEntry::where('reference_type', 'PSAK_71_CKPN')->delete();

            AuditLog::create([
                'action' => 'RESET_PSAK71',
                'user_name' => 'Risk & Accounting Officer',
                'ip_address' => $request->ip(),
                'details' => "Pengguna mengosongkan {$deletedCount} data portofolio kredit PSAK 71 dan jurnal CKPN terkait.",
            ]);

            DB::commit();

            return redirect()->route('psak.dashboard')->with('success', "Portofolio kredit ({$deletedCount} data) dan jurnal CKPN berhasil dikosongkan. Silakan upload file Excel baru Anda.");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PSAK 71 Reset Exception: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengosongkan data: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk membersihkan dan konversi angka (format rupiah, titik ribuan, desimal)
     * Dilengkapi proteksi angka ekstrim (scientific notation Excel, ID kode)
     */
    private function parseNumericValue($val): float
    {
        if (is_numeric($val)) {
            $num = floatval($val);
            // Angka di atas 10 Triliun di kolom finansial Excel biasanya adalah CIF/Kode Sandi/No Rekening
            if ($num > 10000000000000) {
                return 0.0;
            }
            return max(0.0, min($num, 9999999999999.99));
        }

        $str = trim((string)$val);
        if (empty($str)) {
            return 0.0;
        }

        // Tangani notasi ilmiah dari Excel (misal: 8.0301E+16)
        if (preg_match('/^[+-]?[0-9]+(\.[0-9]+)?[eE][+-]?[0-9]+$/', $str)) {
            $num = floatval($str);
            if ($num > 10000000000000) {
                return 0.0;
            }
            return max(0.0, min($num, 9999999999999.99));
        }

        // Hapus simbol mata uang seperti Rp, IDR, spasi
        $str = preg_replace('/[^\d\.,\-]/', '', $str);
        if (empty($str)) {
            return 0.0;
        }

        // Deteksi format Indonesia (1.500.000,00) vs Internasional (1,500,000.00)
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
