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
        Schema::create('psak_credit_portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();
            $table->string('borrower_name');
            $table->string('facility_type'); // Modal Kerja, Investasi, Sindikasi, KPR
            $table->string('sector')->default('Perdagangan & Manufaktur');
            $table->decimal('principal_limit', 18, 2);
            $table->decimal('outstanding_balance', 18, 2); // EAD (Exposure at Default)
            $table->decimal('collateral_value', 18, 2)->default(0);
            $table->string('collateral_type')->default('Tanah & Bangunan (SHM)');
            $table->integer('dpd')->default(0); // Days Past Due
            $table->boolean('is_restructured')->default(false);
            $table->tinyInteger('stage')->default(1); // 1, 2, 3
            $table->decimal('pd_rate', 6, 2)->default(1.50); // Probability of Default %
            $table->decimal('lgd_rate', 6, 2)->default(45.00); // Loss Given Default %
            $table->decimal('eir', 5, 2)->default(9.50); // Effective Interest Rate %
            $table->decimal('ecl_allowance', 18, 2)->default(0); // Nilai CKPN Wajib Dibentuk
            $table->date('valuation_date');
            $table->timestamps();
        });

        Schema::create('psak_macro_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('scenario_name'); // Baseline, Optimis, Pesimis (Downturn)
            $table->decimal('weight_percentage', 5, 2); // Bobot probabilitas (e.g. 50%, 25%, 25%)
            $table->decimal('bi_rate', 5, 2); // Suku bunga acuan BI %
            $table->decimal('inflation_rate', 5, 2); // Inflasi %
            $table->decimal('gdp_growth', 5, 2); // Pertumbuhan PDB %
            $table->timestamps();
        });

        Schema::create('psak_journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->date('entry_date');
            $table->string('account_code');
            $table->string('account_name');
            $table->decimal('debit', 18, 2)->default(0);
            $table->decimal('credit', 18, 2)->default(0);
            $table->string('description');
            $table->string('reference_type')->default('PSAK_71_CKPN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psak_journal_entries');
        Schema::dropIfExists('psak_macro_parameters');
        Schema::dropIfExists('psak_credit_portfolios');
    }
};
