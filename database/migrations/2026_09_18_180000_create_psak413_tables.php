<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('psak413_credit_portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();
            $table->string('customer_name');
            $table->enum('contract_type', ['MURABAHAH', 'MUSYARAKAH', 'MUDHARABAH', 'IJARAH', 'ISTISHNA', 'QARDH', 'KAFALAH'])->default('MURABAHAH');
            $table->string('sector')->default('Perdagangan & Ritel');
            
            // Financials (Precision 24, 2 for huge banking values)
            $table->decimal('financing_limit', 24, 2)->default(0);
            $table->decimal('outstanding_principal', 24, 2)->default(0);
            $table->decimal('margin_suspended', 24, 2)->default(0); // Margin/Bagi hasil ditangguhkan
            $table->decimal('net_carrying_amount', 24, 2)->default(0); // Nilai Tercatat Neto
            $table->decimal('collateral_value', 24, 2)->default(0);
            $table->string('collateral_type')->default('Tanah & Bangunan (SHM)');
            
            // Risk & Staging Parameters
            $table->integer('dpd')->default(0); // Days Past Due
            $table->boolean('is_restructured')->default(false);
            $table->tinyInteger('stage')->default(1); // 1, 2, 3
            $table->decimal('pd_rate', 8, 4)->default(0); // Probability of Default %
            $table->decimal('lgd_rate', 8, 4)->default(0); // Loss Given Default %
            $table->decimal('profit_rate', 8, 4)->default(10.50); // Nisbah / Imbal Hasil Tahunan %
            
            // Calculated Impairment & Provisions
            $table->decimal('ecl_allowance', 24, 2)->default(0); // CKPN Syariah
            $table->decimal('kafalah_guarantee_amount', 24, 2)->default(0); // Nilai Penjaminan Kafalah
            $table->decimal('kafalah_provision_amount', 24, 2)->default(0); // Provisi Kafalah Terbentuk
            
            $table->date('valuation_date')->nullable();
            $table->timestamps();
        });

        Schema::create('psak413_journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number');
            $table->date('entry_date');
            $table->string('account_code');
            $table->string('account_name');
            $table->string('contract_type')->nullable();
            $table->decimal('debit', 24, 2)->default(0);
            $table->decimal('credit', 24, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('psak413_macro_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('scenario_name')->unique(); // baseline, moderate, severe
            $table->decimal('gdp_growth', 8, 2); // Pertumbuhan PDB Riil (%)
            $table->decimal('inflation_rate', 8, 2); // Inflasi (%)
            $table->decimal('issi_index_change', 8, 2); // Perubahan Indeks Saham Syariah ISSI (%)
            $table->decimal('sbis_yield_rate', 8, 2); // Yield Sukuk / SBIS BI (%)
            $table->decimal('usd_idr_rate', 12, 2); // Kurs USD/IDR
            $table->decimal('sharia_npf_multiplier', 8, 2)->default(1.00); // Pengali NPF
            $table->decimal('stage2_migration_rate', 8, 2)->default(0.00); // % Migrasi ke Stage 2
            $table->decimal('stage3_migration_rate', 8, 2)->default(0.00); // % Migrasi ke Stage 3
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psak413_macro_parameters');
        Schema::dropIfExists('psak413_journal_entries');
        Schema::dropIfExists('psak413_credit_portfolios');
    }
};

