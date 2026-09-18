<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\AuditLog;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::orderBy('due_date', 'asc')->get();
        $totalPrincipal = Loan::sum('principal_amount');
        $totalRemaining = Loan::sum('remaining_balance');
        $activeLoansCount = Loan::count();

        // NPL breakdown
        $kol1 = Loan::where('npl_status', 'like', '%1%')->count();
        $kol2 = Loan::where('npl_status', 'like', '%2%')->count();
        $kol3 = Loan::where('npl_status', 'like', '%3%')->count();

        return view('loans.index', compact('loans', 'totalPrincipal', 'totalRemaining', 'activeLoansCount', 'kol1', 'kol2', 'kol3'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrower_name' => 'required|string|max:255',
            'loan_type' => 'required|string',
            'principal_amount' => 'required|numeric|min:1000000',
            'interest_rate' => 'required|numeric|min:0.1',
            'tenor_months' => 'required|integer|min:1',
        ]);

        $monthlyInterestRate = ($validated['interest_rate'] / 100) / 12;
        $tenor = $validated['tenor_months'];
        $principal = $validated['principal_amount'];

        // Simple monthly installment calculation
        $installment = ($principal * $monthlyInterestRate) / (1 - pow(1 + $monthlyInterestRate, -$tenor));

        $loan = Loan::create([
            'loan_number' => 'LN-' . date('Ymd') . '-' . rand(100, 999),
            'borrower_name' => $validated['borrower_name'],
            'loan_type' => $validated['loan_type'],
            'principal_amount' => $principal,
            'interest_rate' => $validated['interest_rate'],
            'tenor_months' => $tenor,
            'monthly_installment' => $installment,
            'remaining_balance' => $principal,
            'npl_status' => 'Kolektibilitas 1 (Lancar)',
            'disbursed_at' => Carbon::now(),
            'due_date' => Carbon::now()->addMonths($tenor),
        ]);

        AuditLog::create([
            'action' => 'DISBURSE_LOAN',
            'user_name' => 'Loan Officer',
            'ip_address' => $request->ip(),
            'details' => "Penyaluran fasilitas kredit {$loan->loan_number} untuk {$loan->borrower_name} senilai Rp " . number_format($loan->principal_amount, 0, ',', '.'),
        ]);

        return back()->with('success', "Fasilitas pinjaman {$loan->loan_number} berhasil disetujui & dicatat.");
    }
}
