<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankAccount;
use App\Models\BankCard;
use App\Models\Transaction;
use App\Models\Loan;
use App\Models\Investment;
use App\Models\CmsArticle;
use App\Models\AuditLog;
use Carbon\Carbon;

class BankDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bank Accounts
        $acc1 = BankAccount::create([
            'account_number' => '1029-3847-5612',
            'account_name' => 'Eddy Cusuma',
            'account_type' => 'Tabungan Premier',
            'currency' => 'IDR',
            'balance' => 87500000.00,
            'status' => 'Active',
        ]);

        $acc2 = BankAccount::create([
            'account_number' => '2093-8475-1029',
            'account_name' => 'Jane Doe',
            'account_type' => 'Giro Bisnis',
            'currency' => 'IDR',
            'balance' => 145000000.00,
            'status' => 'Active',
        ]);

        $acc3 = BankAccount::create([
            'account_number' => '3049-5820-9941',
            'account_name' => 'PT Mega Sentosa Mandiri',
            'account_type' => 'Corporate Treasury',
            'currency' => 'IDR',
            'balance' => 890000000.00,
            'status' => 'Active',
        ]);

        $acc4 = BankAccount::create([
            'account_number' => '4091-8273-6652',
            'account_name' => 'Charleen Livia',
            'account_type' => 'Deposito 12 Bulan',
            'currency' => 'IDR',
            'balance' => 250000000.00,
            'status' => 'Active',
        ]);

        // 2. Bank Cards (matching Bankdash mockup)
        BankCard::create([
            'bank_account_id' => $acc1->id,
            'card_holder' => 'Eddy Cusuma',
            'card_number' => '3778 8492 5621 1234',
            'card_type' => 'Mastercard',
            'tier' => 'Black Platinum',
            'valid_thru' => '12/28',
            'balance' => 57560000.00,
            'theme_style' => 'blue',
            'is_active' => true,
        ]);

        BankCard::create([
            'bank_account_id' => $acc2->id,
            'card_holder' => 'Jane Doe',
            'card_number' => '1234 9821 4452 5678',
            'card_type' => 'Mastercard',
            'tier' => 'World Elite',
            'valid_thru' => '01/29',
            'balance' => 32000000.00,
            'theme_style' => 'white',
            'is_active' => true,
        ]);

        BankCard::create([
            'bank_account_id' => $acc3->id,
            'card_holder' => 'PT Mega Sentosa',
            'card_number' => '5520 8912 3094 9901',
            'card_type' => 'Visa',
            'tier' => 'Corporate Signature',
            'valid_thru' => '08/30',
            'balance' => 210000000.00,
            'theme_style' => 'dark',
            'is_active' => true,
        ]);

        // 3. Transactions (Past 30 days)
        $transactionsData = [
            [
                'account_id' => $acc1->id,
                'ref' => 'TRX-20260918-001',
                'type' => 'credit',
                'category' => 'Deposit',
                'amount' => 15000000.00,
                'recipient' => 'PT Digital Solusindo',
                'desc' => 'Gaji & Bonus Project Fintech',
                'date' => Carbon::now()->subDays(1),
            ],
            [
                'account_id' => $acc1->id,
                'ref' => 'TRX-20260917-002',
                'type' => 'debit',
                'category' => 'Bill Payment',
                'amount' => 2450000.00,
                'recipient' => 'PLN & Telkom Astinet',
                'desc' => 'Pembayaran Tagihan Listrik & Internet Kantor',
                'date' => Carbon::now()->subDays(2),
            ],
            [
                'account_id' => $acc1->id,
                'ref' => 'TRX-20260916-003',
                'type' => 'credit',
                'category' => 'Transfer',
                'amount' => 8500000.00,
                'recipient' => 'Jemi Wilson',
                'desc' => 'Pelunasan Invoice Vendor #8841',
                'date' => Carbon::now()->subDays(3),
            ],
            [
                'account_id' => $acc1->id,
                'ref' => 'TRX-20260915-004',
                'type' => 'debit',
                'category' => 'Entertainment',
                'amount' => 1250000.00,
                'recipient' => 'The Grand Ballroom Dining',
                'desc' => 'Business Client Dinner Meeting',
                'date' => Carbon::now()->subDays(4),
            ],
            [
                'account_id' => $acc1->id,
                'ref' => 'TRX-20260914-005',
                'type' => 'credit',
                'category' => 'Investment',
                'amount' => 5200000.00,
                'recipient' => 'Kustodian Sentral Efek',
                'desc' => 'Kupon Bulanan Sukuk Negara Ritel SR019',
                'date' => Carbon::now()->subDays(5),
            ],
            [
                'account_id' => $acc1->id,
                'ref' => 'TRX-20260913-006',
                'type' => 'debit',
                'category' => 'Withdrawal',
                'amount' => 3000000.00,
                'recipient' => 'ATM Bersama Mall FX',
                'desc' => 'Tarik Tunai Keperluan Kas Operasional',
                'date' => Carbon::now()->subDays(6),
            ],
            [
                'account_id' => $acc2->id,
                'ref' => 'TRX-20260912-007',
                'type' => 'credit',
                'category' => 'Deposit',
                'amount' => 35000000.00,
                'recipient' => 'Paypal Commerce Global',
                'desc' => 'Payout Revenue Penjualan Software Ekspor',
                'date' => Carbon::now()->subDays(7),
            ],
            [
                'account_id' => $acc2->id,
                'ref' => 'TRX-20260910-008',
                'type' => 'debit',
                'category' => 'Transfer',
                'amount' => 12000000.00,
                'recipient' => 'Randy Press (Director)',
                'desc' => 'Transfer Dividen Kuartalan Direksi',
                'date' => Carbon::now()->subDays(9),
            ],
            [
                'account_id' => $acc3->id,
                'ref' => 'TRX-20260908-009',
                'type' => 'credit',
                'category' => 'Deposit',
                'amount' => 120000000.00,
                'recipient' => 'PT Mitra Sejahtera Utama',
                'desc' => 'Pembayaran Kontrak Pengadaan Q3',
                'date' => Carbon::now()->subDays(11),
            ],
            [
                'account_id' => $acc3->id,
                'ref' => 'TRX-20260905-010',
                'type' => 'debit',
                'category' => 'Fee',
                'amount' => 750000.00,
                'recipient' => 'Bank Service Fee',
                'desc' => 'Biaya Administrasi Pengelolaan Rekening Escrow',
                'date' => Carbon::now()->subDays(14),
            ],
        ];

        foreach ($transactionsData as $tx) {
            Transaction::create([
                'reference_no' => $tx['ref'],
                'bank_account_id' => $tx['account_id'],
                'type' => $tx['type'],
                'category' => $tx['category'],
                'amount' => $tx['amount'],
                'balance_after' => 87500000.00,
                'recipient_sender' => $tx['recipient'],
                'description' => $tx['desc'],
                'transaction_date' => $tx['date']->format('Y-m-d'),
                'status' => 'Completed',
            ]);
        }

        // 4. Loans / Kredit
        Loan::create([
            'loan_number' => 'LN-2026-0901',
            'borrower_name' => 'PT Surya Artha Logistik',
            'loan_type' => 'Kredit Modal Kerja Komersial',
            'principal_amount' => 500000000.00,
            'interest_rate' => 8.50,
            'tenor_months' => 36,
            'monthly_installment' => 15780000.00,
            'remaining_balance' => 380000000.00,
            'npl_status' => 'Kolektibilitas 1 (Lancar)',
            'disbursed_at' => Carbon::now()->subMonths(8),
            'due_date' => Carbon::now()->addMonths(28),
        ]);

        Loan::create([
            'loan_number' => 'LN-2026-0842',
            'borrower_name' => 'CV Mandiri Mitra Tehnik',
            'loan_type' => 'Kredit Investasi Mesin & Pabrik',
            'principal_amount' => 250000000.00,
            'interest_rate' => 9.25,
            'tenor_months' => 24,
            'monthly_installment' => 11450000.00,
            'remaining_balance' => 180000000.00,
            'npl_status' => 'Kolektibilitas 1 (Lancar)',
            'disbursed_at' => Carbon::now()->subMonths(6),
            'due_date' => Carbon::now()->addMonths(18),
        ]);

        Loan::create([
            'loan_number' => 'LN-2026-0711',
            'borrower_name' => 'Hendra Setiawan',
            'loan_type' => 'KPR Griya Platinum',
            'principal_amount' => 850000000.00,
            'interest_rate' => 6.75,
            'tenor_months' => 120,
            'monthly_installment' => 9750000.00,
            'remaining_balance' => 790000000.00,
            'npl_status' => 'Kolektibilitas 2 (Dalam Perhatian Khusus)',
            'disbursed_at' => Carbon::now()->subMonths(10),
            'due_date' => Carbon::now()->addMonths(110),
        ]);

        // 5. Investments & Treasury
        Investment::create([
            'asset_code' => 'SBN-FR0091',
            'asset_name' => 'Surat Berharga Negara Seri FR0091',
            'asset_type' => 'Surat Berharga Negara',
            'principal_invested' => 1200000000.00,
            'current_value' => 1284000000.00,
            'return_percentage' => 7.00,
            'risk_level' => 'Low',
            'maturity_date' => Carbon::create(2035, 4, 15),
        ]);

        Investment::create([
            'asset_code' => 'SR-019-T3',
            'asset_name' => 'Sukuk Negara Ritel SR019 Tenor 3 Thn',
            'asset_type' => 'Sukuk Syariah',
            'principal_invested' => 650000000.00,
            'current_value' => 688000000.00,
            'return_percentage' => 5.95,
            'risk_level' => 'Low',
            'maturity_date' => Carbon::create(2027, 9, 10),
        ]);

        Investment::create([
            'asset_code' => 'RDPU-MANDIRI',
            'asset_name' => 'Mandiri Pasar Uang Utama Likuid',
            'asset_type' => 'Pasar Uang',
            'principal_invested' => 450000000.00,
            'current_value' => 471500000.00,
            'return_percentage' => 4.80,
            'risk_level' => 'Low',
            'maturity_date' => null,
        ]);

        // 6. CMS Articles
        CmsArticle::create([
            'title' => 'Pembaruan Kebijakan Suku Bunga Deposito & Tabungan Q3 2026',
            'slug' => 'pembaruan-suku-bunga-deposito-q3-2026',
            'category' => 'Kebijakan Suku Bunga',
            'content' => 'Menindaklanjuti keputusan Rapat Dewan Gubernur Bank Indonesia terkait suku bunga acuan, Bank menetapkan penyesuaian suku bunga simpanan berjangka (deposito) dan tabungan per 1 Oktober 2026 demi menjaga daya saing likuiditas serta stabilitas moneter.',
            'author' => 'Direktorat Treasury & Risiko',
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(2),
        ]);

        CmsArticle::create([
            'title' => 'Panduan Keamanan Transaksi Perbankan Digital & Pencegahan Phishing',
            'slug' => 'panduan-keamanan-transaksi-digital-phishing',
            'category' => 'Keamanan Sistem',
            'content' => 'Seluruh petugas dan nasabah diimbau untuk selalu memeriksa keaslian alamat web dan tidak membagikan OTP atau kode PIN kepada pihak manapun termasuk yang mengatasnamakan staf perbankan.',
            'author' => 'Divisi Keamanan Siber (CISO)',
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(5),
        ]);

        CmsArticle::create([
            'title' => 'Jadwal Pemeliharaan Berkala Sistem Core Banking Akhir Pekan',
            'slug' => 'jadwal-maintenance-core-banking-akhir-pekan',
            'category' => 'Pengumuman Resmi',
            'content' => 'Akan dilaksanakan optimalisasi database transaksi core banking pada hari Minggu pukul 01:00 - 04:00 WIB. Selama proses ini berlangsung, layanan transaksi antar-bank BI-FAST dapat mengalami jeda sesaat.',
            'author' => 'IT Operations Team',
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(8),
        ]);

        // 7. Audit Log awal
        AuditLog::create([
            'action' => 'SYSTEM_INIT',
            'user_name' => 'Super Admin Bank',
            'ip_address' => '127.0.0.1',
            'details' => 'Sistem Web Report Bank & CMS berhasil diinisialisasi.',
        ]);
    }
}

