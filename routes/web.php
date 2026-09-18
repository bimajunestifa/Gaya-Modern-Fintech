<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Psak71Controller;
use App\Http\Controllers\PsakStressTestController;
use App\Http\Controllers\PsakReportController;
use App\Http\Controllers\PsakImportController;
use App\Http\Controllers\PsakJournalController;

// 1. Language Switcher (Public)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en', 'ar', 'zh'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('set_locale');

// 2. Authentication Routes (Public / Guest)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

// 3. Protected Core Banking Application Routes (Requires Login)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions & Reports
    Route::get('/transactions', [TransactionReportController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export-csv', [TransactionReportController::class, 'exportCsv'])->name('transactions.export_csv');
    Route::get('/statement/{id?}', [TransactionReportController::class, 'statement'])->name('transactions.statement');
    Route::post('/quick-transfer', [TransactionReportController::class, 'quickTransfer'])->name('transactions.quick_transfer');

    // CSV / Excel Import Feature
    Route::get('/import-csv', [CsvImportController::class, 'index'])->name('csv.index');
    Route::get('/download-template', [CsvImportController::class, 'downloadTemplate'])->name('csv.download_template');
    Route::get('/download-template-excel', [CsvImportController::class, 'downloadTemplateExcel'])->name('csv.download_template_excel');
    Route::post('/import-csv', [CsvImportController::class, 'upload'])->name('csv.upload');

    // Accounts & Cards
    Route::get('/accounts', [BankAccountController::class, 'index'])->name('accounts.index');
    Route::post('/accounts', [BankAccountController::class, 'store'])->name('accounts.store');

    // Loans & Credit
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');

    // Investments & Treasury
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');

    // CMS & Announcements
    Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
    Route::post('/cms', [CmsController::class, 'store'])->name('cms.store');
    Route::delete('/cms/{id}', [CmsController::class, 'destroy'])->name('cms.destroy');

    // Audit Trail Log
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');

    // Settings & User Profile
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // PSAK 71 & Risk Portfolio Analysis
    Route::prefix('psak')->name('psak.')->group(function () {
        Route::get('/dashboard', [Psak71Controller::class, 'index'])->name('dashboard');
        Route::get('/stresstest', [PsakStressTestController::class, 'index'])->name('stresstest');
        Route::post('/stresstest/simulate', [PsakStressTestController::class, 'simulate'])->name('stresstest.simulate');
        Route::get('/reports', [PsakReportController::class, 'index'])->name('reports');
        Route::get('/import', [PsakImportController::class, 'index'])->name('import');
        Route::get('/download-template', [PsakImportController::class, 'downloadTemplate'])->name('download_template');
        Route::get('/download-template-excel', [PsakImportController::class, 'downloadTemplateExcel'])->name('download_template_excel');
        Route::post('/import', [PsakImportController::class, 'upload'])->name('upload');
        Route::post('/reset', [PsakImportController::class, 'resetData'])->name('reset');
        Route::get('/journals', [PsakJournalController::class, 'index'])->name('journals');
    });
});
