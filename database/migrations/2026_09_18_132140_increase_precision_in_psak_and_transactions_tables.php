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
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `psak_credit_portfolios` MODIFY `principal_limit` DECIMAL(24,2) NOT NULL DEFAULT 0, MODIFY `outstanding_balance` DECIMAL(24,2) NOT NULL DEFAULT 0, MODIFY `collateral_value` DECIMAL(24,2) NOT NULL DEFAULT 0, MODIFY `ecl_allowance` DECIMAL(24,2) NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE `psak_journal_entries` MODIFY `debit` DECIMAL(24,2) NOT NULL DEFAULT 0, MODIFY `credit` DECIMAL(24,2) NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE `transactions` MODIFY `amount` DECIMAL(24,2) NOT NULL DEFAULT 0, MODIFY `balance_after` DECIMAL(24,2) NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE `bank_accounts` MODIFY `balance` DECIMAL(24,2) NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE `bank_cards` MODIFY `balance` DECIMAL(24,2) NOT NULL DEFAULT 0');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
