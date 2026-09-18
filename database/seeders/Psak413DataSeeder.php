<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Psak413CreditPortfolio;
use App\Models\Psak413MacroParameter;
use App\Models\Psak413JournalEntry;
use Carbon\Carbon;

class Psak413DataSeeder extends Seeder
{
    /**
     * Run the database seeds for PSAK 413.
     */
    public function run(): void
    {
        // 1. Seed Macro Parameters for Sharia Stress Testing
        Psak413MacroParameter::truncate();
        
        Psak413MacroParameter::create([
            'scenario_name' => 'baseline',
            'gdp_growth' => 5.15,
            'inflation_rate' => 2.75,
            'issi_index_change' => 6.50,
            'sbis_yield_rate' => 6.25,
            'usd_idr_rate' => 15650.00,
            'sharia_npf_multiplier' => 1.00,
            'stage2_migration_rate' => 0.00,
            'stage3_migration_rate' => 0.00,
        ]);

        Psak413MacroParameter::create([
            'scenario_name' => 'moderate',
            'gdp_growth' => 3.80,
            'inflation_rate' => 4.90,
            'issi_index_change' => -4.20,
            'sbis_yield_rate' => 7.50,
            'usd_idr_rate' => 16400.00,
            'sharia_npf_multiplier' => 1.35,
            'stage2_migration_rate' => 8.50,
            'stage3_migration_rate' => 3.20,
        ]);

        Psak413MacroParameter::create([
            'scenario_name' => 'severe',
            'gdp_growth' => 1.50,
            'inflation_rate' => 7.80,
            'issi_index_change' => -15.50,
            'sbis_yield_rate' => 9.25,
            'usd_idr_rate' => 17250.00,
            'sharia_npf_multiplier' => 2.10,
            'stage2_migration_rate' => 22.00,
            'stage3_migration_rate' => 11.50,
        ]);

        // 2. Seed Realistic Sharia Portfolios (Murabahah, Musyarakah, Mudharabah, Ijarah, Istishna, Qardh, Kafalah)
        Psak413CreditPortfolio::truncate();

        $portfolios = [
            [
                'account_number' => 'SYR-MRB-2026-001',
                'customer_name' => 'PT Amanah Mandiri Sejahtera',
                'contract_type' => 'MURABAHAH',
                'sector' => 'Perdagangan Besar & Grosir',
                'financing_limit' => 12500000000.00,
                'outstanding_principal' => 10800000000.00,
                'margin_suspended' => 1200000000.00,
                'collateral_value' => 14000000000.00,
                'collateral_type' => 'Tanah & Bangunan Gudang (SHGB)',
                'dpd' => 0,
                'is_restructured' => false,
                'profit_rate' => 9.50,
                'kafalah_guarantee_amount' => 0,
            ],
            [
                'account_number' => 'SYR-MSY-2026-002',
                'customer_name' => 'PT Nusantara Agro Lestari',
                'contract_type' => 'MUSYARAKAH',
                'sector' => 'Pertanian & Perkebunan Sawit',
                'financing_limit' => 25000000000.00,
                'outstanding_principal' => 22500000000.00,
                'margin_suspended' => 0.00,
                'collateral_value' => 28000000000.00,
                'collateral_type' => 'Lahan HGU Perkebunan & Pabrik',
                'dpd' => 15,
                'is_restructured' => false,
                'profit_rate' => 11.00,
                'kafalah_guarantee_amount' => 0,
            ],
            [
                'account_number' => 'SYR-MDB-2026-003',
                'customer_name' => 'CV Berkah Tekstil Utama',
                'contract_type' => 'MUDHARABAH',
                'sector' => 'Industri Manufaktur Tekstil',
                'financing_limit' => 8500000000.00,
                'outstanding_principal' => 7200000000.00,
                'margin_suspended' => 0.00,
                'collateral_value' => 6000000000.00,
                'collateral_type' => 'Mesin Tenun Industri & Persediaan',
                'dpd' => 45, // SICR / Stage 2
                'is_restructured' => true,
                'profit_rate' => 12.50,
                'kafalah_guarantee_amount' => 0,
            ],
            [
                'account_number' => 'SYR-IJR-2026-004',
                'customer_name' => 'RS Islam Medika Insani',
                'contract_type' => 'IJARAH',
                'sector' => 'Kesehatan & Alat Medis',
                'financing_limit' => 15000000000.00,
                'outstanding_principal' => 13500000000.00,
                'margin_suspended' => 0.00,
                'collateral_value' => 16500000000.00,
                'collateral_type' => 'Peralatan MRI & CT-Scan Medis',
                'dpd' => 0,
                'is_restructured' => false,
                'profit_rate' => 8.75,
                'kafalah_guarantee_amount' => 0,
            ],
            [
                'account_number' => 'SYR-IST-2026-005',
                'customer_name' => 'PT Graha Syariah Propertindo',
                'contract_type' => 'ISTISHNA',
                'sector' => 'Konstruksi & Real Estate',
                'financing_limit' => 18000000000.00,
                'outstanding_principal' => 16200000000.00,
                'margin_suspended' => 1800000000.00,
                'collateral_value' => 19000000000.00,
                'collateral_type' => 'Kawasan Perumahan Syariah',
                'dpd' => 20,
                'is_restructured' => false,
                'profit_rate' => 10.25,
                'kafalah_guarantee_amount' => 0,
            ],
            [
                'account_number' => 'SYR-QRD-2026-006',
                'customer_name' => 'Koperasi Simpan Pinjam Syariah BMT',
                'contract_type' => 'QARDH',
                'sector' => 'Jasa Keuangan Mikro & UMKM',
                'financing_limit' => 1500000000.00,
                'outstanding_principal' => 1250000000.00,
                'margin_suspended' => 0.00,
                'collateral_value' => 1000000000.00,
                'collateral_type' => 'Bilyet Deposito Syariah',
                'dpd' => 0,
                'is_restructured' => false,
                'profit_rate' => 0.00, // Qardh bebas imbalan
                'kafalah_guarantee_amount' => 0,
            ],
            [
                'account_number' => 'SYR-KFL-2026-007',
                'customer_name' => 'PT Mitra Penjaminan Syariah',
                'contract_type' => 'KAFALAH',
                'sector' => 'Penjaminan Proyek Infrastruktur',
                'financing_limit' => 30000000000.00,
                'outstanding_principal' => 0.00,
                'margin_suspended' => 0.00,
                'collateral_value' => 25000000000.00,
                'collateral_type' => 'Standby Letter of Credit Syariah',
                'dpd' => 0,
                'is_restructured' => false,
                'profit_rate' => 2.50, // Ujrah Kafalah
                'kafalah_guarantee_amount' => 30000000000.00,
            ],
            [
                'account_number' => 'SYR-MRB-2026-008',
                'customer_name' => 'CV Sumber Rejeki Logistik',
                'contract_type' => 'MURABAHAH',
                'sector' => 'Transportasi & Logistik',
                'financing_limit' => 6000000000.00,
                'outstanding_principal' => 5400000000.00,
                'margin_suspended' => 600000000.00,
                'collateral_value' => 3200000000.00,
                'collateral_type' => 'Armada Truk Ekspedisi & BPKB',
                'dpd' => 110, // Stage 3 / Impaired / NPF
                'is_restructured' => false,
                'profit_rate' => 11.50,
                'kafalah_guarantee_amount' => 0,
            ],
        ];

        $today = Carbon::now();

        foreach ($portfolios as $data) {
            $eclResult = Psak413CreditPortfolio::calculateEclSyariah(
                $data['contract_type'],
                $data['outstanding_principal'],
                $data['margin_suspended'],
                $data['dpd'],
                $data['is_restructured'],
                $data['collateral_value'],
                $data['kafalah_guarantee_amount'],
                $data['profit_rate']
            );

            Psak413CreditPortfolio::create([
                'account_number' => $data['account_number'],
                'customer_name' => $data['customer_name'],
                'contract_type' => $data['contract_type'],
                'sector' => $data['sector'],
                'financing_limit' => $data['financing_limit'],
                'outstanding_principal' => $data['outstanding_principal'],
                'margin_suspended' => $data['margin_suspended'],
                'net_carrying_amount' => $eclResult['net_carrying_amount'],
                'collateral_value' => $data['collateral_value'],
                'collateral_type' => $data['collateral_type'],
                'dpd' => $data['dpd'],
                'is_restructured' => $data['is_restructured'],
                'stage' => $eclResult['stage'],
                'pd_rate' => $eclResult['pd_rate'],
                'lgd_rate' => $eclResult['lgd_rate'],
                'profit_rate' => $eclResult['profit_rate'],
                'ecl_allowance' => $eclResult['ecl_allowance'],
                'kafalah_guarantee_amount' => $data['kafalah_guarantee_amount'],
                'kafalah_provision_amount' => $eclResult['kafalah_provision_amount'],
                'valuation_date' => $today,
            ]);
        }

        // 3. Generate initial PSAK 413 Accounting Journals
        Psak413JournalEntry::truncate();
        
        $totalEcl = Psak413CreditPortfolio::sum('ecl_allowance');
        $totalKafalah = Psak413CreditPortfolio::sum('kafalah_provision_amount');
        
        $entryNo = 'JV-PSAK413-' . date('Ymd') . '-001';

        // Jurnal 1: Pembentukan CKPN Syariah
        Psak413JournalEntry::create([
            'entry_number' => $entryNo,
            'entry_date' => $today,
            'account_code' => '5.1.01.01',
            'account_name' => 'Beban Penurunan Nilai Pembiayaan Syariah (ECL PSAK 413)',
            'contract_type' => 'MULTI_AKAD',
            'debit' => $totalEcl,
            'credit' => 0.00,
            'description' => 'Pembentukan penyisihan kerugian penurunan nilai (ECL) portofolio pembiayaan syariah periode ' . $today->format('F Y'),
        ]);

        Psak413JournalEntry::create([
            'entry_number' => $entryNo,
            'entry_date' => $today,
            'account_code' => '1.4.09.01',
            'account_name' => 'Cadangan Kerugian Penurunan Nilai (CKPN) Pembiayaan Syariah',
            'contract_type' => 'MULTI_AKAD',
            'debit' => 0.00,
            'credit' => $totalEcl,
            'description' => 'Kontra Aset Neraca - Akumulasi CKPN Pembiayaan Syariah PSAK 413',
        ]);

        // Jurnal 2: Pembentukan Provisi Kafalah
        if ($totalKafalah > 0) {
            $entryNo2 = 'JV-PSAK413-' . date('Ymd') . '-002';
            Psak413JournalEntry::create([
                'entry_number' => $entryNo2,
                'entry_date' => $today,
                'account_code' => '5.1.02.01',
                'account_name' => 'Beban Pembentukan Provisi Kafalah Penjaminan Kredit',
                'contract_type' => 'KAFALAH',
                'debit' => $totalKafalah,
                'credit' => 0.00,
                'description' => 'Pencadangan kewajiban kontinjensi provisi kafalah penjaminan risiko kredit syariah',
            ]);

            Psak413JournalEntry::create([
                'entry_number' => $entryNo2,
                'entry_date' => $today,
                'account_code' => '2.3.05.01',
                'account_name' => 'Liabilitas Kontinjensi - Provisi Kafalah Penjaminan',
                'contract_type' => 'KAFALAH',
                'debit' => 0.00,
                'credit' => $totalKafalah,
                'description' => 'Provisi penjaminan risiko kredit syariah (Kafalah) pada sisi Liabilitas Neraca',
            ]);
        }
    }
}

