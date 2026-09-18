<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PsakCreditPortfolio;
use App\Models\PsakMacroParameter;
use App\Models\PsakJournalEntry;
use Carbon\Carbon;

class PsakDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Skenario Makroekonomi Forward-Looking (PSAK 71)
        PsakMacroParameter::create([
            'scenario_name' => 'Baseline (Skenario Utama)',
            'weight_percentage' => 50.00,
            'bi_rate' => 6.25,
            'inflation_rate' => 2.80,
            'gdp_growth' => 5.10,
        ]);

        PsakMacroParameter::create([
            'scenario_name' => 'Optimis (Ekspansi Ekonomi)',
            'weight_percentage' => 25.00,
            'bi_rate' => 5.50,
            'inflation_rate' => 2.20,
            'gdp_growth' => 5.80,
        ]);

        PsakMacroParameter::create([
            'scenario_name' => 'Pesimis (Downturn / Krisis)',
            'weight_percentage' => 25.00,
            'bi_rate' => 7.50,
            'inflation_rate' => 4.50,
            'gdp_growth' => 3.20,
        ]);

        // 2. Portofolio Kredit Debitur (Stage 1, 2, 3)
        $loans = [
            // Stage 1: Performing (12-Month ECL, DPD 0-30)
            [
                'acc' => 'LN-CORP-2026-001',
                'name' => 'PT Astra Agro Lestari Tbk',
                'facility' => 'Kredit Modal Kerja Sindikasi',
                'sector' => 'Agribisnis & Perkebunan',
                'limit' => 2500000000.00,
                'ead' => 2100000000.00,
                'collateral' => 3500000000.00,
                'collateral_type' => 'Hak Tanggungan Pabrik Sawit',
                'dpd' => 0,
                'restructured' => false,
            ],
            [
                'acc' => 'LN-CORP-2026-002',
                'name' => 'PT Indofood CBP Sukses Makmur',
                'facility' => 'Revolving Working Capital',
                'sector' => 'Manufaktur Makanan & Minuman',
                'limit' => 1800000000.00,
                'ead' => 1450000000.00,
                'collateral' => 2200000000.00,
                'collateral_type' => 'Gudang Distribusi Logistik',
                'dpd' => 5,
                'restructured' => false,
            ],
            [
                'acc' => 'LN-SME-2026-003',
                'name' => 'CV Sumber Makmur Tehnik',
                'facility' => 'Kredit Investasi Mesin CNC',
                'sector' => 'Industri Pengolahan Logam',
                'limit' => 600000000.00,
                'ead' => 520000000.00,
                'collateral' => 750000000.00,
                'collateral_type' => 'Sertifikat Hak Milik (SHM)',
                'dpd' => 12,
                'restructured' => false,
            ],
            [
                'acc' => 'LN-RET-2026-004',
                'name' => 'Budi Pratama, S.T.',
                'facility' => 'KPR Griya Utama',
                'sector' => 'Konsumsi Rumah Tangga',
                'limit' => 850000000.00,
                'ead' => 790000000.00,
                'collateral' => 1100000000.00,
                'collateral_type' => 'Rumah Tinggal BSD City',
                'dpd' => 0,
                'restructured' => false,
            ],

            // Stage 2: Underperforming / SICR (DPD 31-90 atau Restrukturisasi)
            [
                'acc' => 'LN-SME-2026-005',
                'name' => 'PT Citra Kargo Nusantara',
                'facility' => 'Kredit Modal Kerja Armada',
                'sector' => 'Transportasi & Logistik',
                'limit' => 950000000.00,
                'ead' => 880000000.00,
                'collateral' => 700000000.00,
                'collateral_type' => 'Fidusia Armada Truk Box',
                'dpd' => 45, // Terdeteksi SICR
                'restructured' => true,
            ],
            [
                'acc' => 'LN-RET-2026-006',
                'name' => 'Dr. Hendra Wijaya',
                'facility' => 'Kredit Multiguna Usaha Klinik',
                'sector' => 'Jasa Kesehatan',
                'limit' => 400000000.00,
                'ead' => 365000000.00,
                'collateral' => 420000000.00,
                'collateral_type' => 'SHGB Ruko Medika',
                'dpd' => 62, // Terdeteksi SICR
                'restructured' => false,
            ],

            // Stage 3: Non-Performing / Impaired (Default, DPD > 90)
            [
                'acc' => 'LN-CORP-2026-007',
                'name' => 'PT Borneo Coal Energi',
                'facility' => 'Kredit Investasi Pertambangan',
                'sector' => 'Pertambangan & Energi',
                'limit' => 1500000000.00,
                'ead' => 1420000000.00,
                'collateral' => 850000000.00, // Defisit jaminan
                'collateral_type' => 'Alat Berat Excavator & Izin Tambang',
                'dpd' => 115, // Default / Macet
                'restructured' => true,
            ],
        ];

        $totalInitialEcl = 0;

        foreach ($loans as $item) {
            $eclData = PsakCreditPortfolio::calculateEcl(
                $item['ead'],
                $item['dpd'],
                $item['restructured'],
                $item['collateral']
            );

            $totalInitialEcl += $eclData['ecl_allowance'];

            PsakCreditPortfolio::create([
                'account_number' => $item['acc'],
                'borrower_name' => $item['name'],
                'facility_type' => $item['facility'],
                'sector' => $item['sector'],
                'principal_limit' => $item['limit'],
                'outstanding_balance' => $item['ead'],
                'collateral_value' => $item['collateral'],
                'collateral_type' => $item['collateral_type'],
                'dpd' => $item['dpd'],
                'is_restructured' => $item['restructured'],
                'stage' => $eclData['stage'],
                'pd_rate' => $eclData['pd_rate'],
                'lgd_rate' => $eclData['lgd_rate'],
                'eir' => 9.75,
                'ecl_allowance' => $eclData['ecl_allowance'],
                'valuation_date' => Carbon::now()->format('Y-m-d'),
            ]);
        }

        // 3. Jurnal Akuntansi PSAK Awal
        PsakJournalEntry::create([
            'entry_number' => 'JV-PSAK71-' . date('Ymd') . '-001',
            'entry_date' => Carbon::now()->format('Y-m-d'),
            'account_code' => '51.10.01',
            'account_name' => 'Beban Penyisihan Kerugian Penurunan Nilai Kredit (ECL)',
            'debit' => $totalInitialEcl,
            'credit' => 0,
            'description' => 'Pembentukan cadangan kerugian penurunan nilai portofolio kredit Q3 (PSAK 71)',
            'reference_type' => 'PSAK_71_CKPN',
        ]);

        PsakJournalEntry::create([
            'entry_number' => 'JV-PSAK71-' . date('Ymd') . '-002',
            'entry_date' => Carbon::now()->format('Y-m-d'),
            'account_code' => '13.90.01',
            'account_name' => 'Cadangan Kerugian Penurunan Nilai (CKPN) - Kontra Aset',
            'debit' => 0,
            'credit' => $totalInitialEcl,
            'description' => 'Akumulasi CKPN kredit perbankan Stage 1, 2, dan 3 (PSAK 71)',
            'reference_type' => 'PSAK_71_CKPN',
        ]);
    }
}

