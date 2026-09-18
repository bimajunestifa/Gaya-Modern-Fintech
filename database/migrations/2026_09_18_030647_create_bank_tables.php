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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();
            $table->string('account_name');
            $table->string('account_type')->default('Tabungan'); // Tabungan, Giro, Deposito, Bisnis
            $table->string('currency')->default('IDR');
            $table->decimal('balance', 18, 2)->default(0);
            $table->string('status')->default('Active'); // Active, Dormant, Blocked
            $table->timestamps();
        });

        Schema::create('bank_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->nullable()->constrained()->onDelete('set null');
            $table->string('card_holder');
            $table->string('card_number');
            $table->string('card_type')->default('Mastercard'); // Mastercard, Visa, GPN
            $table->string('tier')->default('Platinum'); // Platinum, Gold, Black
            $table->string('valid_thru')->default('12/28');
            $table->decimal('balance', 18, 2)->default(0);
            $table->string('theme_style')->default('blue'); // blue, dark, white
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('bank_account_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'debit']); // credit = Uang Masuk, debit = Uang Keluar
            $table->string('category'); // Deposit, Transfer, Withdrawal, Bill Payment, Investment, Fee, Loan
            $table->decimal('amount', 18, 2);
            $table->decimal('balance_after', 18, 2)->default(0);
            $table->string('recipient_sender')->nullable();
            $table->string('description');
            $table->date('transaction_date');
            $table->enum('status', ['Completed', 'Pending', 'Failed'])->default('Completed');
            $table->timestamps();
        });

        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_number')->unique();
            $table->string('borrower_name');
            $table->string('loan_type'); // Modal Kerja, Investasi, Konsumtif, KPR
            $table->decimal('principal_amount', 18, 2);
            $table->decimal('interest_rate', 5, 2); // %
            $table->integer('tenor_months');
            $table->decimal('monthly_installment', 18, 2);
            $table->decimal('remaining_balance', 18, 2);
            $table->string('npl_status')->default('Kolektibilitas 1 (Lancar)'); // Kol 1 - Kol 5
            $table->date('disbursed_at');
            $table->date('due_date');
            $table->timestamps();
        });

        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('asset_name');
            $table->string('asset_type'); // Surat Berharga Negara, Reksadana, Pasar Uang, Saham
            $table->decimal('principal_invested', 18, 2);
            $table->decimal('current_value', 18, 2);
            $table->decimal('return_percentage', 6, 2);
            $table->string('risk_level')->default('Low'); // Low, Moderate, High
            $table->date('maturity_date')->nullable();
            $table->timestamps();
        });

        Schema::create('cms_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category'); // Pengumuman, Kebijakan Suku Bunga, Keamanan Sistem, Info Layanan
            $table->text('content');
            $table->string('author')->default('Admin Operasional');
            $table->boolean('is_published')->default(true);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // UPLOAD_CSV, EXPORT_REPORT, TRANSACTION_CREATE, dll
            $table->string('user_name')->default('Bank Officer');
            $table->string('ip_address')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('cms_articles');
        Schema::dropIfExists('investments');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('bank_cards');
        Schema::dropIfExists('bank_accounts');
    }
};
