<?php

namespace App\Http\Controllers\Psak413;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Psak413CreditPortfolio;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Psak413ImportController extends Controller
{
    public function index()
    {
        $totalPortfolios = Psak413CreditPortfolio::count();
        $recentPortfolios = Psak413CreditPortfolio::latest()->take(5)->get();
        return view('psak413.import', compact('totalPortfolios', 'recentPortfolios'));
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=template_import_psak413_syariah.csv',
        ];

        $columns = [
            'Nomor Rekening',
            'Nama Nasabah / Debitur',
            'Jenis Akad (MURABAHAH/MUSYARAKAH/MUDHARABAH/IJARAH/ISTISHNA/QARDH/KAFALAH)',
            'Sektor Industri',
            'Plafon Pembiayaan (IDR)',
            'Saldo Pokok / Nilai Investasi (IDR)',
            'Margin Ditangguhkan (IDR)',
            'Nilai Agunan Syariah (IDR)',
            'Jenis Agunan',
            'DPD (Hari Tunggakan)',
            'Restrukturisasi (YA/TIDAK)',
            'Nisbah / Imbal Hasil (%)',
            'Nilai Penjaminan Kafalah (IDR)',
        ];

        $callback = function () use ($columns) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, $columns, ';');

            $samples = [
                ['SYR-MRB-901', 'PT Barakah Berdikari', 'MURABAHAH', 'Perdagangan Retail', '5000000000', '4200000000', '400000000', '5000000000', 'Tanah SHM & Toko', '0', 'TIDAK', '10.5', '0'],
                ['SYR-MSY-902', 'PT Agro Syariah Makmur', 'MUSYARAKAH', 'Agrikultur & Sawit', '12000000000', '10500000000', '0', '14000000000', 'Lahan HGU Perkebunan', '15', 'TIDAK', '11.0', '0'],
                ['SYR-MDB-903', 'CV Halal Food Nusantara', 'MUDHARABAH', 'Industri Makanan Halal', '3500000000', '3100000000', '0', '2500000000', 'Mesin Produksi & Gudang', '45', 'YA', '12.0', '0'],
                ['SYR-IJR-904', 'Klinik Syariah Sehat', 'IJARAH', 'Kesehatan & Farmasi', '4000000000', '3600000000', '0', '4500000000', 'Peralatan Medis', '0', 'TIDAK', '9.0', '0'],
                ['SYR-KFL-905', 'PT Jaminan Syariah Mandiri', 'KAFALAH', 'Konstruksi Proyek', '10000000000', '0', '0', '8000000000', 'Bank Garansi Syariah', '0', 'TIDAK', '2.5', '10000000000'],
            ];

            foreach ($samples as $row) {
                fputcsv($handle, $row, ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadTemplateExcel()
    {
        return $this->downloadTemplate();
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // CSV/TXT up to 10MB
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Detect delimiter
            $firstLine = fgets($handle);
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
            rewind($handle);

            // Skip header
            fgetcsv($handle, 4096, $delimiter);

            while (($data = fgetcsv($handle, 4096, $delimiter)) !== false) {
                if (!empty($data[0]) && !empty($data[1])) {
                    $rows[] = $data;
                }
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return back()->with('error', 'File import kosong atau format kolom tidak sesuai.');
        }

        DB::beginTransaction();
        try {
            $inserted = 0;
            $today = Carbon::now();

            foreach ($rows as $row) {
                $accountNo = trim($row[0]);
                $customerName = trim($row[1] ?? 'Nasabah Syariah');
                $contractType = strtoupper(trim($row[2] ?? 'MURABAHAH'));
                $sector = trim($row[3] ?? 'Perdagangan');
                $limit = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $row[4] ?? 0);
                $outstanding = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $row[5] ?? 0);
                $marginSuspended = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $row[6] ?? 0);
                $collateral = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $row[7] ?? 0);
                $collateralType = trim($row[8] ?? 'Tanah & Bangunan (SHM)');
                $dpd = (int) ($row[9] ?? 0);
                $isRestructured = in_array(strtoupper(trim($row[10] ?? '')), ['YA', 'YES', '1', 'TRUE']);
                $profitRate = (float) str_replace(['%', ' '], '', $row[11] ?? 10.5);
                $kafalahGuarantee = (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $row[12] ?? 0);

                if (!in_array($contractType, ['MURABAHAH', 'MUSYARAKAH', 'MUDHARABAH', 'IJARAH', 'ISTISHNA', 'QARDH', 'KAFALAH'])) {
                    $contractType = 'MURABAHAH';
                }

                $calc = Psak413CreditPortfolio::calculateEclSyariah(
                    $contractType,
                    $outstanding,
                    $marginSuspended,
                    $dpd,
                    $isRestructured,
                    $collateral,
                    $kafalahGuarantee,
                    $profitRate
                );

                Psak413CreditPortfolio::updateOrCreate(
                    ['account_number' => $accountNo],
                    [
                        'customer_name' => $customerName,
                        'contract_type' => $contractType,
                        'sector' => $sector,
                        'financing_limit' => $limit,
                        'outstanding_principal' => $outstanding,
                        'margin_suspended' => $marginSuspended,
                        'net_carrying_amount' => $calc['net_carrying_amount'],
                        'collateral_value' => $collateral,
                        'collateral_type' => $collateralType,
                        'dpd' => $dpd,
                        'is_restructured' => $isRestructured,
                        'stage' => $calc['stage'],
                        'pd_rate' => $calc['pd_rate'],
                        'lgd_rate' => $calc['lgd_rate'],
                        'profit_rate' => $calc['profit_rate'],
                        'ecl_allowance' => $calc['ecl_allowance'],
                        'kafalah_guarantee_amount' => $kafalahGuarantee,
                        'kafalah_provision_amount' => $calc['kafalah_provision_amount'],
                        'valuation_date' => $today,
                    ]
                );

                $inserted++;
            }

            AuditLog::create([
                'action' => 'PSAK413_BATCH_IMPORT',
                'user_name' => Auth::check() ? Auth::user()->name : 'Bank Officer',
                'ip_address' => $request->ip(),
                'details' => "Berhasil mengimpor {$inserted} portofolio pembiayaan syariah PSAK 413.",
            ]);

            DB::commit();
            return back()->with('success', "Sukses mengimpor {$inserted} data fasilitas pembiayaan syariah PSAK 413!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses file import: ' . $e->getMessage());
        }
    }

    public function resetData()
    {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'Psak413DataSeeder']);
        return back()->with('success', 'Data portofolio & parameter PSAK 413 berhasil di-reset ke data acuan awal!');
    }
}

